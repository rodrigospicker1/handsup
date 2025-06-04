<div 
  x-data="{ show: true }" 
  x-show="show" 
  x-init="setTimeout(() => show = false, 4000)"
  class="absolute bottom-5 right-5 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300 z-50 bg-success"
>
  <div class="flex items-center bg-success">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
    </svg>
    <span>{{ $message }}</span>
  </div>
</div>
