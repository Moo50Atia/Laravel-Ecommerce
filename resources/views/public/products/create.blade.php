@extends('layouts.app')

@section('style')
<style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
@endsection

@section('content')
@section('style')

  <style>
    body { font-family: 'Cairo', sans-serif; }
  </style>
@endsection

@section('script')
<script>
function generateImageFields() {
    const count = document.getElementById('imageCount').value;
    const container = document.getElementById('imageFieldsContainer');
    container.innerHTML = '';

    for (let i = 1; i <= count; i++) {
        const div = document.createElement('div');
        div.classList.add('mb-2');

        const label = document.createElement('label');
        label.textContent = `صورة إضافية ${i}`;
        label.classList.add('block', 'text-gray-700');

        const input = document.createElement('input');
        input.type = 'file';
        input.name = `additional_images[]`; // دي تبعتهم Array
        input.classList.add('border', 'p-2', 'rounded', 'w-full');

        div.appendChild(label);
        div.appendChild(input);
        container.appendChild(div);
    }
}
</script>
@endsection