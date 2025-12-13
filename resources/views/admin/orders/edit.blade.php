@extends('layouts.app')

@section('style')
<style>
            .form-card {
                transition: all 0.3s ease;
            }
            .form-card:hover {
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-badge {
                transition: all 0.3s ease;
            }
            .status-badge:hover {
                transform: scale(1.05);
            }
        </style>
@endsection

@section('content')
@section('style')
        <style>
            .form-card {
                transition: all 0.3s ease;
            }
            .form-card:hover {
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            .status-badge {
                transition: all 0.3s ease;
            }
            .status-badge:hover {
                transform: scale(1.05);
            }
        </style>
@endsection

@section('script')
<script>
            // Store original status for reset functionality
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.getElementById('status');
                if (statusSelect) {
                    statusSelect.dataset.originalStatus = '{{ $order->status }}';
                }
            });
        </script>
        @vite(['resources/js/admin/order-edit.js'])
@endsection