{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.user_type.auth')

@section('content')


<button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
Toggle modal
</button>

<div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                <button data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
            </div>
        </div>
    </div>
</div>



<div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
  <div class="flex justify-between">
    <div>
      <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">32.4k</h5>
      <p class="text-base font-normal text-gray-500 dark:text-gray-400">Users this week</p>
    </div>
    <div
      class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
      12%
      <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
      </svg>
    </div>
  </div>
  <div id="area-chart"></div>
  <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
    <div class="flex justify-between items-center pt-5">
      <!-- Button -->
      <button
        id="dropdownDefaultButton"
        data-dropdown-toggle="lastDaysdropdown"
        data-dropdown-placement="bottom"
        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
        type="button">
        Last 7 days
        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
      </button>
      <!-- Dropdown menu -->
      <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 7 days</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 30 days</a>
            </li>
            <li>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 90 days</a>
            </li>
          </ul>
      </div>
      <a
        href="#"
        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
        Users Report
        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
      </a>
    </div>
  </div>
</div>
<style>#chart {
  max-width: 650px;
  margin: 35px auto;
}
</style>
 @push('scripts')
<script>
  var options = {
  chart: {
    height: 280,
    type: "area"
  },
  dataLabels: {
    enabled: false
  },
  series: [
    {
      name: "Series 1",
      data: [45, 52, 38, 45, 19, 23, 2]
    }
  ],
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.9,
      stops: [0, 90, 100]
    }
  },
  xaxis: {
    categories: [
      "01 Jan",
      "02 Jan",
      "03 Jan",
      "04 Jan",
      "05 Jan",
      "06 Jan",
      "07 Jan"
    ]
  }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();


</script>
@endpush
  {{-- RESUMOS --}}
  <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    {{-- Card Cashbook --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 flex flex-col justify-between border border-gray-200 dark:border-gray-700">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full shadow">
          <i class="fas fa-wallet text-green-600 dark:text-green-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-green-700 dark:text-green-400">Cashbook</h2>
      </div>
      <div class="mt-6 text-center">
        <span class="text-4xl font-extrabold text-green-700 dark:text-green-400 drop-shadow">
          R$ {{ number_format($totalCashbook ?? 0, 2, ',', '.') }}
        </span>
        <div class="mt-4 flex justify-center space-x-8 text-sm">
          <div class="space-y-1">
            <p class="text-gray-400 dark:text-gray-400">Receitas</p>
            <p class="text-green-600 dark:text-green-400 font-semibold">
              R$ {{ number_format($totalReceitas ?? 0, 2, ',', '.') }}
            </p>
          </div>
          <div class="space-y-1">
            <p class="text-gray-400 dark:text-gray-400">Despesas</p>
            <p class="text-red-600 dark:text-red-400 font-semibold">
              R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}
            </p>
          </div>
        </div>
      </div>
      <div class="mt-6 space-y-2 text-sm text-gray-500 dark:text-gray-300">
        <div class="flex justify-between">
          <span>Média diária</span>
          <span class="font-medium text-gray-700 dark:text-gray-100">
            R$ {{ $mediaDiariaCashbook ? number_format($mediaDiariaCashbook, 2, ',', '.') : '0,00' }}
          </span>
        </div>
        <div class="flex justify-between">
          <span>Última movimentação</span>
          @if($ultimaMovimentacaoCashbook)
            <div class="text-right">
              <p class="font-medium">{{ \Carbon\Carbon::parse($ultimaMovimentacaoCashbook->date)->format('d/m/Y') }}</p>
              <p class="{{ $ultimaMovimentacaoCashbook->type_id == 1 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-bold">
                R$ {{ number_format($ultimaMovimentacaoCashbook->value, 2, ',', '.') }}
              </p>
            </div>
          @else
            <span>—</span>
          @endif
        </div>
      </div>
      <a href="{{ url('dashboard/cashbook') }}"
         class="mt-6 inline-block text-sm font-semibold text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 transition">
        Ver detalhes &rarr;
      </a>
    </div>

    {{-- Card Produtos --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 flex flex-col justify-between border border-gray-200 dark:border-gray-700">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full shadow">
          <i class="fas fa-box text-blue-600 dark:text-blue-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-blue-700 dark:text-blue-400">Produtos</h2>
      </div>
      <div class="mt-6 text-center">
        <span class="text-4xl font-extrabold text-blue-700 dark:text-blue-400 drop-shadow">{{ $totalProdutos ?? 0 }}</span>
        <div class="mt-4 flex justify-center space-x-8 text-sm">
          <div class="space-y-1">
            <p class="text-gray-400 dark:text-gray-400">Em estoque</p>
            <p class="text-blue-600 dark:text-blue-400 font-semibold">{{ $totalProdutosEstoque ?? 0 }}</p>
          </div>
          <div class="space-y-1">
            <p class="text-gray-400 dark:text-gray-400">Sem estoque</p>
            <p class="text-red-600 dark:text-red-400 font-semibold">{{ $totalProdutosSemEstoque ?? 0 }}</p>
          </div>
        </div>
      </div>
      <div class="mt-6 space-y-2 text-sm text-gray-500 dark:text-gray-300">
        <div class="flex justify-between">
          <span>Maior estoque</span>
          <span class="font-medium text-blue-700 dark:text-blue-400">
            {{ $produtoMaiorEstoque->name ?? '—' }}
          </span>
        </div>
        <div class="flex justify-between">
          <span>Mais vendido</span>
          <span class="font-medium text-green-700 dark:text-green-400">
            {{ $produtoMaisVendido->name ?? '—' }}
          </span>
        </div>
      </div>
      <a href="{{ url('dashboard/products') }}"
         class="mt-6 inline-block text-sm font-semibold text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition">
        Ver detalhes &rarr;
      </a>
    </div>

    {{-- Card Vendas --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 flex flex-col justify-between border border-gray-200 dark:border-gray-700">
      <div class="flex items-center space-x-4">
        <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full shadow">
          <i class="fas fa-shopping-cart text-yellow-600 dark:text-yellow-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-yellow-700 dark:text-yellow-400">Vendas</h2>
      </div>
      <div class="mt-6 text-center">
        <span class="text-4xl font-extrabold text-yellow-700 dark:text-yellow-400 drop-shadow">
          R$ {{ number_format($totalFaturamento ?? 0, 2, ',', '.') }}
        </span>
      </div>
      <div class="mt-6 space-y-1 text-sm text-gray-500 dark:text-gray-300">
        <div class="flex justify-between">
          <span>Valor faltante</span>
          <span class="font-semibold text-red-600 dark:text-red-400">
            R$ {{ number_format($totalFaltante ?? 0, 2, ',', '.') }}
          </span>
        </div>
        <div class="flex justify-between">
          <span>Clientes</span>
          <span class="font-semibold text-blue-700 dark:text-blue-400">{{ $totalClientes ?? 0 }}</span>
        </div>
        <div class="flex justify-between">
          <span>Pendentes</span>
          <span class="font-semibold text-yellow-700 dark:text-yellow-400">{{ $clientesComSalesPendentes ?? 0 }}</span>
        </div>
        <div class="flex justify-between">
          <span>Última venda</span>
          @if($ultimaVenda)
            <span class="font-medium text-green-700 dark:text-green-400">
              {{ \Carbon\Carbon::parse($ultimaVenda->created_at)->format('d/m') }} –
              R$ {{ number_format($ultimaVenda->total_price, 2, ',', '.') }}
            </span>
          @else
            <span>—</span>
          @endif
        </div>
      </div>
      <a href="{{ url('dashboard/sales') }}"
         class="mt-6 inline-block text-sm font-semibold text-yellow-700 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 transition">
        Ver detalhes &rarr;
      </a>
    </div>
  </section>

  {{-- ATALHOS RÁPIDOS --}}
  <section>


    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Atalhos rápidos</h2>
    <div class="grid gap-4 grid-cols-7">
      @php
      $shortcuts = [
        ['url'=>'banks','icon'=>'fas fa-university','label'=>'Bancos','color'=>'indigo'],
        ['url'=>'clients','icon'=>'fas fa-users','label'=>'Clientes','color'=>'pink'],
        ['url'=>'cofrinho','icon'=>'fas fa-piggy-bank','label'=>'Cofrinho','color'=>'green'],
        ['url'=>'dashboard/products','icon'=>'fas fa-boxes','label'=>'Produtos','color'=>'blue'],
        ['url'=>'dashboard/cashbook','icon'=>'fas fa-wallet','label'=>'Cashbook','color'=>'emerald'],
        ['url'=>'dashboard/sales','icon'=>'fas fa-shopping-cart','label'=>'Vendas','color'=>'yellow'],
        ['url'=>'profile','icon'=>'fas fa-user','label'=>'Perfil','color'=>'gray'],
      ];
      @endphp

      @foreach($shortcuts as $item)
      <a href="{{ url($item['url']) }}"



         class="flowbite-btn flex flex-col items-center p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-{{ $item['color'] }}-50 dark:hover:bg-{{ $item['color'] }}-900 transition">
        <div class="p-3 bg-{{ $item['color'] }}-100 dark:bg-{{ $item['color'] }}-900 rounded-full mb-2">
          <i class="{{ $item['icon'] }} text-2xl text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400"></i>
        </div>

        <span class="text-{{ $item['color'] }}-700 dark:text-{{ $item['color'] }}-400 font-medium">{{ $item['label'] }}</span>
      </a>
      @endforeach
    </div>
  </section>

@endsection

@push('scripts')
@endpush
