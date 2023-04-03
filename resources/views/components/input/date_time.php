<div wire:ignore>
    <input
        x-data
        x-init="flatpickr($refs.input, dateFormat:'Y-m-d', altFormat:'F j, Y', altInput:true );"
        x-ref="input"
        type="text"
        data-input
        class = "block w-full disabled:bg-gray-200 p-2 border border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm sm:leading-5"
    />
</div>