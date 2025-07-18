<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-gray-800 text-center mb-8 text-4xl font-extrabold drop-shadow-md">ポケモン図鑑</h1>

                {{-- エラーメッセージの表示 --}}
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 border border-red-300 p-3 mb-5 rounded-md text-center">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($detailedPokemonList) && count($detailedPokemonList) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-5">
                        @foreach($detailedPokemonList as $pokemon)
                            {{-- 各ポケモンカードを詳細ページへのリンクにする --}}
                            <a href="{{ route('pokemons.show', ['pokemon' => $pokemon['name']]) }}" class="pokemon-card bg-white rounded-lg shadow-md overflow-hidden text-center p-4 transition-all duration-200 ease-in-out border border-gray-200 no-underline text-inherit block hover:translate-y-[-5px] hover:shadow-xl">
                                @if(isset($pokemon['sprites']['front_default']))
                                    <img src="{{ $pokemon['sprites']['front_default'] }}" alt="{{ $pokemon['name'] }}" class="max-w-[100px] h-auto mx-auto mb-3 block">
                                @else
                                    <img src="https://placehold.co/100x100/eeeeee/333333?text=No+Image" alt="No Image" class="max-w-[100px] h-auto mx-auto mb-3 block">
                                @endif
                                <h2 class="text-xl text-gray-700 mb-1 capitalize">{{ ucfirst($pokemon['name']) }}</h2>
                                <p class="text-sm text-gray-600 mb-1">ID: {{ $pokemon['id'] }}</p>
                                <p class="text-sm text-gray-600 mb-1">高さ: {{ $pokemon['height'] / 10 }} m</p>
                                <p class="text-sm text-gray-600 mb-1">重さ: {{ $pokemon['weight'] / 10 }} kg</p>

                                <h3 class="text-base text-gray-700 mt-2">タイプ</h3>
                                <ul class="list-none p-0 mt-2 flex flex-wrap justify-center gap-1">
                                    @foreach($pokemon['types'] as $typeInfo)
                                        <li class="bg-blue-100 text-blue-700 inline-block px-2 py-1 rounded-full m-0.5 text-xs capitalize shadow-sm">
                                            {{ ucfirst($typeInfo['type']['name']) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 text-lg p-12">ポケモンデータが見つかりませんでした。</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
