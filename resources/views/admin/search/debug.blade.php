@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
            {{-- FORM QUERY + FILTER --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Debug Pencarian Surat</h1>

                <form action="{{ route('admin.search.simple') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
                    <input type="text" name="query" requiblue 5
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ketik kata kunci..." value="{{ request('query') }}">

                    <select name="filter"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>Semua Surat</option>
                        <option value="masuk" {{ ($filter ?? 'all') === 'masuk' ? 'selected' : '' }}>Hanya Surat Masuk
                        </option>
                        <option value="keluar"{{ ($filter ?? 'all') === 'keluar' ? 'selected' : '' }}>Hanya Surat Keluar
                        </option>
                    </select>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition cursor-pointer">
                        Proses
                    </button>
                </form>
            </div>

            @if (isset($query))
                {{-- TABEL 1: DATA Surat & QUERY --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">1. Data Surat & Query</h2>

                    {{-- Baris Query --}}
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center text-lg font-semibold">Query</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-3 text-center text-2xl">{{ $query }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center font-semibold">No</th>
                                    <th class="border p-3 font-semibold">ID</th>
                                    <th class="border p-3 font-semibold">Tipe</th>
                                    <th class="border p-3 font-semibold">Isi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($documents as $index => $doc)
                                    <tr class="transition hover:bg-blue-50">
                                        <td class="border p-3 text-center">{{ $index + 1 }}</td>
                                        <td class="border p-3">{{ $doc['id'] }}</td>
                                        <td class="border p-3 text-center">
                                            {{ str_contains($doc['id'], 'SM') ? 'Masuk' : 'Keluar' }}
                                        </td>
                                        <td class="border p-3">{{ $doc['isi'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 2: PREPROCESSING DETAIL --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h1 class="mb-4 text-2xl font-bold">2. Hasil Preprocessing Surat</h1>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center font-semibold">No</th>
                                    <th class="border p-3 text-center font-semibold">Tipe Surat</th>
                                    <th class="border p-3 font-semibold">Isi</th>
                                    <th class="border p-3 font-semibold">Case Folding</th>
                                    <th class="border p-3 font-semibold">Tokenizing</th>
                                    <th class="border p-3 font-semibold">Filtering</th>
                                    <th class="border p-3 font-semibold">Stopword Removal</th>
                                    <th class="border p-3 font-semibold">Stemming</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($documentsdetailed as $idx => $doc)
                                    <tr class="transition hover:bg-blue-50">
                                        <td class="border p-3 text-center">{{ $idx + 1 }}</td>
                                        <td class="border p-3 text-center">{{ $doc['tipe'] }}</td>
                                        <td class="border p-3">{{ $doc['isi'] }}</td>
                                        <td class="border p-3">{{ $doc['preprocessing']['case_folding'] }}</td>
                                        <td class="border p-3">|
                                            {{ implode(' | ', $doc['preprocessing']['tokenizing']) }} |
                                        </td>
                                        <td class="border p-3">| {{ implode(' | ', $doc['preprocessing']['filtering']) }} |
                                        </td>
                                        <td class="border p-3">|
                                            {{ implode(' | ', $doc['preprocessing']['stopword_removal']) }} |
                                        </td>
                                        <td class="border p-3">| {{ implode(' | ', $doc['preprocessing']['stemming']) }} |
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 3: TOKENS PER Surat --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">3. Tokens per Surat</h2>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">No</th>
                                <th class="border p-3 font-semibold">Data</th>
                                <th class="border p-3 font-semibold">Tokens</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($documents as $index => $doc)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $index + 1 }}</td>
                                    <td class="border p-3">
                                        ID: {{ $doc['id'] }}<br>
                                        Tipe: {{ str_contains($doc['id'], 'SM') ? 'Masuk' : 'Keluar' }}<br>
                                        Isi: {{ $doc['isi'] }}
                                    </td>
                                    <td class="border p-3">{{ implode(' | ', $doc['tokens']) }}</td>
                                </tr>
                            @endforeach

                            {{-- Baris Query --}}
                            <tr class="bg-yellow-100 font-semibold transition hover:bg-blue-50">
                                <td class="border p-3 text-center">Q</td>
                                <td class="border p-3">Query: {{ $query }}</td>
                                <td class="border p-3">{{ implode(', ', $queryTokens) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 4: TF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">4. Term Frequency (TF)</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-blue-500 bg-blue-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-blue-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-blue-100 px-2 py-1 text-base">
                            TF(t,d) = jumlah kemunculan term t dalam dokumen d
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Hitung <strong>raw count</strong> – berapa kali kata t muncul di dokumen d.</li>
                            <li>Nilai minimum = 0 (kata tidak ada), nilai maksimum = tak terbatas.</li>
                            <li>Tabel di bawah menunjukkan frekuensi absolut setiap kata pada 20 dokumen + query.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 5: TF WEIGHT --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">5. Term Frequency Weight (TF Weight)</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-green-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-green-100 px-2 py-1 text-base">
                            W(t,d) = 1 + log₁₀(TF(t,d)) &nbsp; &nbsp; jika TF > 0<br>
                            0 &nbsp; &nbsp; jika TF = 0
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Menekan lonjakan frekuensi: kata yang muncul 10× tidak 10× lebih penting dari 1×.</li>
                            <li>Hasil ≥ 0; jika TF = 1 → W = 1; TF = 10 → W ≈ 2; TF = 100 → W = 3.</li>
                            <li>Langkah ini menghasilkan bobot <em>logaritmik</em> sebelum dikalikan IDF.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfWeightTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 6: DF & IDF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">6. Document Frequency (DF) dan Inverse Document Frequency (IDF)
                    </h2>
                    <div class="mb-6 rounded-lg border-l-4 border-yellow-500 bg-yellow-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-yellow-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-yellow-100 px-2 py-1 text-base">
                            DF(t) = banyak dokumen yang mengandung term t<br>
                            IDF(t) = log₁₀(N / DF(t))
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>DF = hitung dokumen (bukan kalimat) yang mengandung kata t.</li>
                            <li>N = total dokumen (di sini 20).</li>
                            <li>IDF tinggi → kata jarang; IDF rendah → kata umum (mis. “dan”, “di”).</li>
                            <li>Stop-word biasanya IDF ≈ 0 karena muncul di hampir semua dokumen.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                <th class="border p-3 text-center font-semibold">DF</th>
                                <th class="border p-3 text-center font-semibold">IDF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($idfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    <td class="border p-3 text-center">{{ $row['df'] }}</td>
                                    <td class="border p-3 text-center">{{ $row['idf'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 7: TF-IDF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">7. TF-IDF</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-purple-500 bg-purple-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-purple-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-purple-100 px-2 py-1 text-base">
                            TF-IDF(t,d) = W(t,d) × IDF(t)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Menggabungkan “seberapa sering” (TF-Weight) dan “seberapa unik” (IDF).</li>
                            <li>Kata yang sering muncul DI DOKUMEN INI tapi JARANG di dokumen lain → nilai tinggi.</li>
                            <li>Kata yang muncul di semua dokumen → IDF ≈ 0 → TF-IDF ≈ 0 (tidak penting).</li>
                            <li>Hasil digunakan sebagai bobot vektor untuk perhitungan similarity.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfidfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 8: TF-IDF NORMALISASI --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">8. TF-IDF Normalisasi</h2>

                    <div class="mb-6 rounded-lg border-l-4 border-indigo-500 bg-indigo-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-indigo-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-indigo-100 px-2 py-1 text-base">
                            TF-IDF<sub>norm</sub>(t,d) =
                            TF-IDF(t,d) / √(∑ TF-IDF(tᵢ,d)²)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Melakukan normalisasi panjang vektor dokumen dan query.</li>
                            <li>Setiap dokumen dan query memiliki panjang vektor = 1.</li>
                            <li>Menghilangkan bias dokumen panjang.</li>
                            <li>Nilai inilah yang digunakan langsung pada Cosine Similarity.</li>
                        </ul>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-600 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfidfNormTable as $row)
                                <tr class="transition hover:bg-indigo-50">
                                    <td class="border p-3 text-center font-medium">
                                        {{ $row['term'] }}
                                    </td>

                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">
                                            {{ $row["D$j"] }}
                                        </td>
                                    @endfor

                                    <td class="border p-3 text-center font-semibold">
                                        {{ $row['Q'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 9: COSINE SIMILARITY --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">9. Cosine Similarity ({{ ucfirst($filter) }})</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-indigo-500 bg-indigo-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-indigo-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-indigo-100 px-2 py-1 text-base">
                            cos θ = (Vq · Vd) / (|Vq| × |Vd|)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Vq = vektor TF-IDF query; Vd = vektor TF-IDF dokumen.</li>
                            <li>Titik (·) = dot-product; |V| = panjang vektor (akar dari jumlah kuadrat elemen).</li>
                            <li>Hasil 0–1: 1 = sangat mirip, 0 = tidak ada kesamaan.</li>
                            <li>Menentukan peringkat dokumen yang paling relevan terhadap query.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Dokumen</th>
                                <th class="border p-3 text-center font-semibold">Tipe</th>
                                <th class="border p-3 text-center font-semibold">Cosine Similarity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($cosineSimilarities as $idx => $sim)
                                @php
                                    // ambil index asli (0-19) lalu cek tipe-nya
                                    $realIdx = (int) substr($sim['doc'], 1) - 1; // "D4" → 3
                                    $tipe = $documents[$realIdx]['tipe']; // masuk / keluar
                                @endphp
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $sim['doc'] }}</td>
                                    <td class="border p-3 text-center">
                                        <span class="rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold">
                                            {{ ucfirst($tipe) }}
                                        </span>
                                    </td>
                                    <td class="border p-3 text-center">{{ $sim['similarity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 10: JACCARD SIMILARITY --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">10. Jaccard Similarity ({{ ucfirst($filter) }})</h2>

                    <div class="mb-4 rounded border-l-4 border-teal-500 bg-teal-50 p-4 text-sm">
                        <p class="mb-2 font-bold text-teal-800">Rumus & Penjelasan</p>
                        <code class="text-lg">J(A,B) = |A ∩ B| / |A ∪ B|</code>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-gray-700">
                            <li>A = set kata dokumen, B = set kata query (unik).</li>
                            <li>Nilai 0–1; tidak peduli frekuensi, hanya keberadaan kata.</li>
                            <li>Cocok untuk pencarian yang menekan kata umum (stop-word tetap 0 jika di-set).</li>
                        </ul>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Dokumen</th>
                                <th class="border p-3 text-center font-semibold">Tipe</th>
                                <th class="border p-3 text-center font-semibold">Jaccard Similarity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($jaccardSimilarities as $jac)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $jac['doc'] }}</td>
                                    <td class="border p-3 text-center">
                                        <span class="rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold">
                                            {{ ucfirst($jac['tipe']) }}
                                        </span>
                                    </td>
                                    <td class="border p-3 text-center">{{ $jac['jaccard'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (isset($confusionMatrix) && isset($comparisonMetrics))
                    <div class="mt-8 bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                                Confusion Matrix & Evaluasi Performa
                            </h3>
                            @if (isset($confusionMatrix['winner']))
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold 
                            {{ $confusionMatrix['winner'] === 'cosine'
                                ? 'bg-blue-100 text-blue-800'
                                : ($confusionMatrix['winner'] === 'jaccard'
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                                    Pemenang: {{ ucfirst($confusionMatrix['winner']) }}
                                </span>
                            @endif
                        </div>

                        {{-- Penjelasan Confusion Matrix --}}
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Apa itu Confusion Matrix?
                            </h4>
                            <p class="text-sm text-gray-700 mb-2">
                                Confusion Matrix adalah tabel yang menunjukkan performa algoritma pencarian dengan
                                membandingkan hasil prediksi dengan kondisi sebenarnya (ground truth).
                            </p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                                <div class="p-2 bg-green-100 rounded">
                                    <span class="font-semibold text-green-800">TP (True Positive)</span><br>
                                    Dokumen relevan dan diprediksi relevan
                                </div>
                                <div class="p-2 bg-red-100 rounded">
                                    <span class="font-semibold text-red-800">FP (False Positive)</span><br>
                                    Dokumen tidak relevan tapi diprediksi relevan
                                </div>
                                <div class="p-2 bg-red-100 rounded">
                                    <span class="font-semibold text-red-800">FN (False Negative)</span><br>
                                    Dokumen relevan tapi diprediksi tidak relevan
                                </div>
                                <div class="p-2 bg-green-100 rounded">
                                    <span class="font-semibold text-green-800">TN (True Negative)</span><br>
                                    Dokumen tidak relevan dan diprediksi tidak relevan
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            {{-- Cosine Confusion Matrix --}}
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-blue-600 mb-3 flex items-center">
                                    <i class="fas fa-calculator mr-2"></i>
                                    Cosine Similarity
                                </h4>

                                <div class="overflow-x-auto mb-4">
                                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th colspan="3"
                                                    class="px-4 py-2 text-center font-medium text-blue-700 border">
                                                    Confusion Matrix
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 bg-gray-50 border text-center"></th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Relevan</th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Tidak
                                                    Relevan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Relevan</td>
                                                <td class="px-4 py-2 text-center bg-green-100 font-bold border">
                                                    TP = {{ $confusionMatrix['cosine']['tp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FN = {{ $confusionMatrix['cosine']['fn'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Tidak Relevan</td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FP = {{ $confusionMatrix['cosine']['fp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-green-100 border">
                                                    TN = {{ $confusionMatrix['cosine']['tn'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Presisi</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['precision'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Akurasi prediksi relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Recall</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['recall'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Kemampuan menemukan dokumen relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">F1-Score</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['f1'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rata-rata harmonik presisi & recall
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Akurasi</div>
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['cosine']['accuracy'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Persentase prediksi benar
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Jaccard Confusion Matrix --}}
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="text-lg font-semibold text-green-600 mb-3 flex items-center">
                                    <i class="fas fa-percentage mr-2"></i>
                                    Jaccard Similarity
                                </h4>

                                <div class="overflow-x-auto mb-4">
                                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                        <thead class="bg-green-50">
                                            <tr>
                                                <th colspan="3"
                                                    class="px-4 py-2 text-center font-medium text-green-700 border">
                                                    Confusion Matrix
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="px-4 py-2 bg-gray-50 border text-center"></th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Relevan</th>
                                                <th class="px-4 py-2 bg-gray-50 border text-center">Diprediksi Tidak
                                                    Relevan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Relevan</td>
                                                <td class="px-4 py-2 text-center bg-green-100 font-bold border">
                                                    TP = {{ $confusionMatrix['jaccard']['tp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FN = {{ $confusionMatrix['jaccard']['fn'] }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 font-medium border">Sebenarnya Tidak Relevan</td>
                                                <td class="px-4 py-2 text-center bg-red-100 border">
                                                    FP = {{ $confusionMatrix['jaccard']['fp'] }}
                                                </td>
                                                <td class="px-4 py-2 text-center bg-green-100 border">
                                                    TN = {{ $confusionMatrix['jaccard']['tn'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Presisi</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['precision'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Akurasi prediksi relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Recall</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['recall'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Kemampuan menemukan dokumen relevan
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">F1-Score</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['f1'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Rata-rata harmonik presisi & recall
                                        </div>
                                    </div>
                                    <div class="text-center p-3 bg-green-50 rounded-lg">
                                        <div class="text-sm text-gray-600">Akurasi</div>
                                        <div class="text-lg font-bold text-green-600">
                                            {{ number_format($confusionMatrix['jaccard']['accuracy'] * 100, 1) }}%
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Persentase prediksi benar
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Perbandingan Metrik --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-balance-scale mr-2"></i>
                                Perbandingan Performa Algoritma
                            </h4>

                            <div class="overflow-x-auto mb-6">
                                <table class="min-w-full divide-y divide-gray-200 border text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 border text-center font-medium">Metrik</th>
                                            <th class="px-4 py-2 border text-center font-medium text-blue-600">Cosine
                                                Similarity</th>
                                            <th class="px-4 py-2 border text-center font-medium text-green-600">Jaccard
                                                Similarity</th>
                                            <th class="px-4 py-2 border text-center font-medium">Pemenang</th>
                                            <th class="px-4 py-2 border text-center font-medium">Selisih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $cosineMetrics = $confusionMatrix['cosine'] ?? [];
                                            $jaccardMetrics = $confusionMatrix['jaccard'] ?? [];
                                        @endphp

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Presisi</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['precision'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['precision'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['precision'] > $jaccardMetrics['precision'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['precision'] < $jaccardMetrics['precision'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['precision'] - $jaccardMetrics['precision']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Recall</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['recall'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['recall'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['recall'] > $jaccardMetrics['recall'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['recall'] < $jaccardMetrics['recall'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['recall'] - $jaccardMetrics['recall']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">F1-Score</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['f1'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['f1'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['f1'] > $jaccardMetrics['f1'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['f1'] < $jaccardMetrics['f1'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['f1'] - $jaccardMetrics['f1']) * 100, 1) }}%
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 border font-medium">Akurasi</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($cosineMetrics['accuracy'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format($jaccardMetrics['accuracy'] * 100, 1) }}%</td>
                                            <td class="px-4 py-2 border text-center">
                                                @if ($cosineMetrics['accuracy'] > $jaccardMetrics['accuracy'])
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Cosine</span>
                                                @elseif($cosineMetrics['accuracy'] < $jaccardMetrics['accuracy'])
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">Jaccard</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">Seri</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                {{ number_format(abs($cosineMetrics['accuracy'] - $jaccardMetrics['accuracy']) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Ringkasan Performa --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-600">Total Dokumen Dianalisis</span>
                                        <span class="font-bold">{{ $confusionMatrix['total_documents'] ?? 0 }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">Semua dokumen yang sesuai kriteria filter</div>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-blue-600">Skor Rata-rata Cosine</span>
                                        <span class="font-bold text-blue-600">
                                            {{ number_format($confusionMatrix['average_scores']['cosine'] * 100, 1) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-blue-500">Semakin tinggi berarti ranking relevansi lebih baik
                                    </div>
                                </div>

                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-green-600">Skor Rata-rata Jaccard</span>
                                        <span class="font-bold text-green-600">
                                            {{ number_format($confusionMatrix['average_scores']['jaccard'] * 100, 1) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-green-500">Semakin tinggi berarti overlap token lebih baik
                                    </div>
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-yellow-600">Threshold</span>
                                        <span class="font-bold text-yellow-600">
                                            {{ number_format($confusionMatrix['threshold'] * 100, 0) }}%
                                        </span>
                                    </div>
                                    <div class="text-xs text-yellow-500">Nilai minimum untuk prediksi "relevan"</div>
                                </div>
                            </div>

                            {{-- Keterangan Penting --}}
                            <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <h5 class="font-semibold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    Cara Membaca Hasil Evaluasi:
                                </h5>
                                <ul class="text-sm text-gray-700 space-y-1 pl-5 list-disc">
                                    <li><strong>Presisi tinggi</strong> berarti algoritma jarang salah mengklasifikasikan
                                        dokumen tidak relevan sebagai relevan</li>
                                    <li><strong>Recall tinggi</strong> berarti algoritma dapat menemukan hampir semua
                                        dokumen yang relevan</li>
                                    <li><strong>F1-Score</strong> adalah keseimbangan antara Presisi dan Recall (idealnya
                                        tinggi keduanya)</li>
                                    <li><strong>Ground Truth</strong> ditentukan berdasarkan overlap token antara query dan
                                        dokumen (minimal 20% token query harus ada di dokumen)</li>
                                    <li><strong>Threshold 10%</strong> berarti dokumen dengan similarity ≥ 0.1 dianggap
                                        "relevan" oleh algoritma</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @elseif(isset($query) && empty($cosineResults) && empty($jaccardResults))
                    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                        <div class="flex">
                            <div class="shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-yellow-800">Evaluasi Performa Tidak Tersedia</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Tidak ada hasil pencarian yang ditemukan untuk query
                                        "<strong>{{ $query }}</strong>"</p>
                                    <p class="mt-1">Confusion Matrix hanya bisa dihitung ketika ada hasil dari minimal
                                        satu algoritma.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

{{-- Tambahkan ini di bagian scripts --}}
@push('styles')
    <style>
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi progress bars
            const progressBars = document.querySelectorAll('.h-2.bg-blue-600, .h-2.bg-green-600');
            progressBars.forEach(bar => {
                const originalWidth = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = originalWidth;
                }, 300);
            });
        });
    </script>
@endpush
