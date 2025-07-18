<x-app-layout>
    <div class="py-12 bg-gray-100"> {{-- 背景色を少し明るくして、カードとのコントラストを出す --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- flex justify-center items-center min-h-screen bg-blue-50 p-5 を削除または修正 --}}
                {{-- x-app-layout の中で画面中央に配置されるため、これらのクラスは不要か、調整が必要 --}}
                <div class="flex flex-col items-center p-5"> {{-- 縦方向に要素を並べ、中央揃えにする --}}
                    <div class="move-detail-card bg-white p-8 rounded-2xl shadow-2xl max-w-xl w-full text-center border border-blue-200 relative">

                        {{-- 戻るボタンをカードの上部に配置し、Tailwindでマージンを調整 --}}
                        <div class="mb-6 text-left"> {{-- ボタンを左寄せにするためのラッパー --}}
                            <a href="{{ route('pokemons.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg no-underline text-sm transition-colors duration-300 hover:bg-blue-700">
                                ← ポケモン一覧に戻る
                            </a>
                        </div>

                        <h1 class="text-gray-800 text-center mb-5 text-5xl font-extrabold capitalize drop-shadow-lg">
                            技: {{ $japaneseMoveName }}
                        </h1>

                        {{-- 技の簡単な説明 (英語) --}}
                        @if(isset($move['effect_entries'][0]['effect']))
                            <p class="text-gray-700 mb-6 text-left">{{ $move['effect_entries'][0]['effect'] }}</p> {{-- 説明文も左寄せに --}}
                        @endif

                        {{-- 技のタイプ --}}
                        @if(isset($move['type']['name']))
                            <div class="mb-4 text-left"> {{-- タイプも左寄せに --}}
                                <span class="font-bold text-gray-600">タイプ:</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm capitalize">{{ ucfirst($move['type']['name']) }}</span>
                            </div>
                        @endif

                        {{-- 技を覚えるポケモン --}}
                        <h2 class="section-title text-3xl text-gray-700 mt-8 mb-4 border-b-2 border-gray-200 pb-2">この技を覚えるポケモン</h2>
                        @if(count($learnedByPokemon) > 0)
                            <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <ul class="list-items list-none p-0 flex flex-wrap gap-2 justify-center">
                                    @foreach($learnedByPokemon as $pokemon)
                                        <li class="bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-base capitalize shadow-sm">
                                            <a href="{{ $pokemon['url'] }}" class="text-green-800 hover:underline">
                                                {{ ucfirst($pokemon['name']) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-gray-600">この技を覚えるポケモンはいません。</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
