<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-center items-center min-h-screen bg-blue-50 p-5">
                    <div class="pokemon-detail-card bg-white p-8 rounded-2xl shadow-2xl max-w-xl w-full text-center border border-blue-200 relative">
                        {{-- 一覧ページに戻るボタン --}}
                        <a href="{{ route('pokemons.index') }}" class="back-button absolute top-5 left-5 bg-blue-600 text-white px-4 py-2 rounded-lg no-underline text-sm transition-colors duration-300 hover:bg-blue-700">
                            ← 一覧に戻る
                        </a>

                        {{-- ポケモン名 --}}
                        <h1 class="text-gray-800 text-center mb-5 text-5xl font-extrabold capitalize drop-shadow-lg">
                            {{ ucfirst($pokemon['name'] ?? '不明なポケモン') }}
                        </h1>

                        {{-- ポケモン画像 --}}
                        @if(isset($pokemon['sprites']['front_default']))
                            <img src="{{ $pokemon['sprites']['front_default'] }}" alt="{{ $pokemon['name'] }}" class="pokemon-image max-w-[200px] h-auto mx-auto mb-6 block border-2 border-gray-300 rounded-full bg-gray-50 p-2 shadow-md">
                        @else
                            <img src="https://placehold.co/200x200/eeeeee/333333?text=No+Image" alt="No Image" class="pokemon-image max-w-[200px] h-auto mx-auto mb-6 block border-2 border-gray-300 rounded-full bg-gray-50 p-2 shadow-md">
                        @endif

                        {{-- 鳴き声再生ボタン --}}
                        @if(isset($pokemon['cries']['latest']))
                            <div class="mb-6">
                                <audio controls class="w-full max-w-xs mx-auto">
                                    <source src="{{ $pokemon['cries']['latest'] }}" type="audio/ogg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        @else
                            <p class="text-gray-600 mb-6">鳴き声データがありません。</p>
                        @endif

                        {{-- 基本情報 --}}
                        <div class="info-grid grid grid-cols-2 gap-4 mb-6 text-left">
                            <div class="info-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <span class="font-bold text-gray-600 block mb-1 text-sm">ID:</span>
                                <p class="m-0 text-lg text-gray-800">{{ $pokemon['id'] ?? 'N/A' }}</p>
                            </div>
                            <div class="info-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <span class="font-bold text-gray-600 block mb-1 text-sm">高さ:</span>
                                <p class="m-0 text-lg text-gray-800">{{ isset($pokemon['height']) ? ($pokemon['height'] / 10) . ' m' : 'N/A' }}</p>
                            </div>
                            <div class="info-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <span class="font-bold text-gray-600 block mb-1 text-sm">重さ:</span>
                                <p class="m-0 text-lg text-gray-800">{{ isset($pokemon['weight']) ? ($pokemon['weight'] / 10) . ' kg' : 'N/A'}}</p>
                            </div>
                            <div class="info-item bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <span class="font-bold text-gray-600 block mb-1 text-sm">経験値:</span>
                                <p class="m-0 text-lg text-gray-800">{{ $pokemon['base_experience'] ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- タイプ --}}
                        <h2 class="section-title text-3xl text-gray-700 mt-8 mb-4 border-b-2 border-gray-200 pb-2">タイプ</h2>
                        @if(isset($pokemon['types']) && count($pokemon['types']) > 0)
                            <ul class="list-items list-none p-0 flex flex-wrap gap-2 justify-center">
                                @foreach($pokemon['types'] as $typeInfo)
                                    <li class="bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-base capitalize shadow-sm">
                                        {{ ucfirst($typeInfo['type']['name']) }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-600">タイプ情報がありません。</p>
                        @endif

                        {{-- 特性 --}}
                        <h2 class="section-title text-3xl text-gray-700 mt-8 mb-4 border-b-2 border-gray-200 pb-2">特性</h2>
                        @if(isset($pokemon['abilities']) && count($pokemon['abilities']) > 0)
                            <ul class="list-items list-none p-0 flex flex-wrap gap-2 justify-center">
                                @foreach($pokemon['abilities'] as $abilityInfo)
                                    <li class="bg-teal-100 text-teal-800 px-3 py-1.5 rounded-full text-base capitalize shadow-sm">
                                        {{ ucfirst($abilityInfo['ability']['name']) }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-600">特性情報がありません。</p>
                        @endif
                        {{-- 技 --}}
                        <h2 class="section-title text-3xl text-gray-700 mt-8 mb-4 border-b-2 border-gray-200 pb-2">覚える技</h2>
                        @if(isset($pokemon['moves']) && count($pokemon['moves']) > 0)
                            <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <ul class="list-items list-none p-0 flex flex-wrap gap-2 justify-center">
                                    @foreach($pokemon['moves'] as $moveInfo)
                                        <li class="bg-purple-100 text-purple-800 px-3 py-1.5 rounded-full text-base capitalize shadow-sm">
                                            {{ ucfirst(str_replace('-', ' ', $moveInfo['move']['name'])) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-gray-600">覚える技情報がありません。</p>
                        @endif

                        {{-- 種族値 --}}
                        <h2 class="section-title text-3xl text-gray-700 mt-8 mb-4 border-b-2 border-gray-200 pb-2">種族値</h2>
                        @if(isset($pokemon['stats']) && count($pokemon['stats']) > 0)
                            <div>
                                @foreach($pokemon['stats'] as $statInfo)
                                    <div class="stat-item mb-3 text-left">
                                        <span class="stat-name font-bold capitalize text-gray-600 text-base">{{ ucfirst(str_replace('-', ' ', $statInfo['stat']['name'])) }}:</span>
                                        <span class="stat-value float-right font-bold text-gray-700">{{ $statInfo['base_stat'] }}</span>
                                        <div class="stat-bar-container w-full bg-gray-200 rounded-full h-2.5 mt-1 overflow-hidden">
                                            {{-- ベース値を最大255としてパーセンテージを計算 --}}
                                            <div class="stat-bar h-full bg-green-500 rounded-full transition-all duration-500 ease-in-out" style="width: {{ ($statInfo['base_stat'] / 255) * 100 }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">種族値情報がありません。</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
