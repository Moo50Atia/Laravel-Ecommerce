<div class="container">
    <h2>Create orders</h2>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">user_id</label>
            <input type="text" class="form-control" name="user_id" value="{{old("user_id")}}">
            @error("user_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="vendor_id" class="form-label">vendor_id</label>
            <input type="text" class="form-control" name="vendor_id" value="{{old("vendor_id")}}">
            @error("vendor_id")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="order_number" class="form-label">order_number</label>
            <input type="text" class="form-control" name="order_number" value="{{old("order_number")}}">
            @error("order_number")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="status" class="form-label">status</label>
            <input type="text" class="form-control" name="status" value="{{old("status")}}">
            @error("status")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="total_amount" class="form-label">total_amount</label>
            <input type="text" class="form-control" name="total_amount" value="{{old("total_amount")}}">
            @error("total_amount")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="discount_amount" class="form-label">discount_amount</label>
            <input type="text" class="form-control" name="discount_amount" value="{{old("discount_amount")}}">
            @error("discount_amount")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="shipping_amount" class="form-label">shipping_amount</label>
            <input type="text" class="form-control" name="shipping_amount" value="{{old("shipping_amount")}}">
            @error("shipping_amount")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="grand_total" class="form-label">grand_total</label>
            <input type="text" class="form-control" name="grand_total" value="{{old("grand_total")}}">
            @error("grand_total")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="payment_method" class="form-label">payment_method</label>
            <input type="text" class="form-control" name="payment_method" value="{{old("payment_method")}}">
            @error("payment_method")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="payment_status" class="form-label">payment_status</label>
            <input type="text" class="form-control" name="payment_status" value="{{old("payment_status")}}">
            @error("payment_status")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="shipping_address" class="form-label">shipping_address</label>
            <input type="text" class="form-control" name="shipping_address" value="{{old("shipping_address")}}">
            @error("shipping_address")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="billing_address" class="form-label">billing_address</label>
            <input type="text" class="form-control" name="billing_address" value="{{old("billing_address")}}">
            @error("billing_address")
                <p>{{$message}}</p>
            @enderror
        </div>
<div class="mb-3">
            <label for="notes" class="form-label">notes</label>
            <input type="text" class="form-control" name="notes" value="{{old("notes")}}">
            @error("notes")
                <p>{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>