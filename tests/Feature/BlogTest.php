<?php

use App\Models\Blog;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Blog Model', function () {
    it('can create a blog with factory', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'author_id' => $user->id,
            'title' => 'Test Blog',
            'content' => 'Test content',
        ]);
        
        expect($blog)->toBeInstanceOf(Blog::class)
            ->and($blog->title)->toBe('Test Blog')
            ->and($blog->content)->toBe('Test content');
    });
});

describe('Blog Relationships', function () {
    it('belongs to a user', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['author_id' => $user->id]);
        
        expect($blog->author)->toBeInstanceOf(User::class)
            ->and($blog->author->id)->toBe($user->id);
    });
});

describe('Blog ForAdmin Scope', function () {
    it('allows superadmin to see all blogs', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $blog1 = Blog::factory()->create([
            'author_id' => $user1->id,
            'title' => 'Blog from City A',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $blog2 = Blog::factory()->create([
            'author_id' => $user2->id,
            'title' => 'Blog from City B',
        ]);
        
        // Create superadmin
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        
        // Apply ForAdmin scope
        $blogs = Blog::query()->ForAdmin($superAdmin)->get();
        
        // Superadmin should see all blogs
        expect($blogs)->toHaveCount(2);
    });
    
    it('restricts admin to see only blogs from users in their city', function () {
        // Create users in different cities
        $user1 = User::factory()->create();
        $address1 = UserAddress::factory()->create([
            'user_id' => $user1->id,
            'city' => 'City A',
        ]);
        $blog1 = Blog::factory()->create([
            'author_id' => $user1->id,
            'title' => 'Blog from City A',
        ]);
        
        $user2 = User::factory()->create();
        $address2 = UserAddress::factory()->create([
            'user_id' => $user2->id,
            'city' => 'City B',
        ]);
        $blog2 = Blog::factory()->create([
            'author_id' => $user2->id,
            'title' => 'Blog from City B',
        ]);
        
        // Create admin in City A
        $admin = User::factory()->create(['role' => 'admin']);
        $adminAddress = UserAddress::factory()->create([
            'user_id' => $admin->id,
            'city' => 'City A',
        ]);
        
        // Apply ForAdmin scope
        $blogs = Blog::query()->ForAdmin($admin)->get();
        
        // Admin should only see blogs from users in City A
        expect($blogs)->toHaveCount(1)
            ->and($blogs->first()->title)->toBe('Blog from City A');
    });
});