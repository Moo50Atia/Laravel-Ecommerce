@extends('layouts.app')

@section('style')
<style>
            .order-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card {
                transition: all 0.3s ease;
            }
            .stats-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-timeline {
                position: relative;
            }
            .status-timeline::before {
                content: '';
                position: absolute;
                left: 20px;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #e5e7eb;
            }
            .timeline-item {
                position: relative;
                padding-left: 50px;
                margin-bottom: 20px;
            }
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 12px;
                top: 0;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #3b82f6;
                border: 3px solid #fff;
                box-shadow: 0 0 0 3px #e5e7eb;
            }
            .timeline-item.active::before {
                background: #10b981;
                box-shadow: 0 0 0 3px #d1fae5;
            }
        </style>
@endsection

@section('content')
@section('style')
        <style>
            .order-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .stats-card {
                transition: all 0.3s ease;
            }
            .stats-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            .product-card {
                transition: all 0.3s ease;
            }
            .product-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-timeline {
                position: relative;
            }
            .status-timeline::before {
                content: '';
                position: absolute;
                left: 20px;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #e5e7eb;
            }
            .timeline-item {
                position: relative;
                padding-left: 50px;
                margin-bottom: 20px;
            }
            .timeline-item::before {
                content: '';
                position: absolute;
                left: 12px;
                top: 0;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #3b82f6;
                border: 3px solid #fff;
                box-shadow: 0 0 0 3px #e5e7eb;
            }
            .timeline-item.active::before {
                background: #10b981;
                box-shadow: 0 0 0 3px #d1fae5;
            }
        </style>
@endsection