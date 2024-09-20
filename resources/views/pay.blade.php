@extends('layouts.app')
@section('content')

<div class="w-full max-w-lg mx-auto">
  <form>
    <div class="p-6 bg-white rounded-lg">
      <h2 class="mb-4 text-xl font-medium">Payment</h2>
      <div class="mb-4">
        <label class="block mb-2 font-medium text-gray-700" for="card_number">
          Credit Card Number
        </label>
        <input x-mask:dynamic="creditCardMask" class="w-full p-2 bg-gray-200 rounded-lg" id="card_number" name="card_number"
          type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19"
          placeholder="xxxx xxxx xxxx xxxx" required>
      </div>
      <div class="mb-4">
        <label class="block mb-2 font-medium text-gray-700" for="expiration_date">
          Expiration Date
        </label>
        <input x-mask="99/99" class="w-full p-2 bg-gray-200 rounded-lg" type="text" id="expiration_date"
          name="expiration_date">
      </div>
      <div class="mb-4">
        <label class="block mb-2 font-medium text-gray-700" for="cvc">
          CVC
        </label>
        <input class="w-full p-2 bg-gray-200 rounded-lg" type="text" id="cvc" name="cvc">
      </div>
      <div class="mb-4">
        <label class="block mb-2 font-medium text-gray-700" for="buying_user">
          Buying User
        </label>
        <input class="w-full p-2 bg-gray-200 rounded-lg" type="email" id="buying_user" name="buying_user"
          value="{{ session('encrypted_email') }}" disabled>
      </div>
      <div class="mb-4">
        <label class="block mb-2 font-medium text-gray-700" for="buying_for">
          Buying for
        </label>
        <input class="w-full p-2 bg-gray-200 rounded-lg" type="email" id="buying_for" name="buying_for"
          value="{{ session('encrypted_email') }}">
      </div>
      <div class="mb-4">
        <label for="country" class="block text-sm font-medium text-gray-700">App</label>
        <div class="mt-1">
          <select id="country" name="country" autocomplete="country-name"
            class="block w-full p-2 bg-gray-200 rounded-lg">
            <option value="MeTag">MeTag</option>
            <option value="MeSort">MeSort</option>
            <option value="Both">Both</option>
          </select>
        </div>
      </div>

      <div class="mb-4 text-xl font-medium">
        Price:
        <span id="price">20€</span> + taxes
      </div>
      <button class="px-4 py-2 font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-700">
        Make Payment
      </button>
    </div>
  </form>
</div>



@endsection
@section('pagespecificscripts')
<script>
  function creditCardMask(input) {
    console.log("does it go here?");
    return input.startsWith('34') || input.startsWith('37')
        ? '9999 999999 99999'
        : '9999 9999 9999 9999'
}
</script>
@endsection