@extends('layouts.public')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-8">
    <div class="max-w-3xl w-full space-y-6">
        <div class="bg-white rounded-xl shadow-md p-6 text-center space-y-4">

            <h1 class="text-2xl font-bold text-gray-900">
                የፈተና ጥሪ ማስታወቂያ
            </h1>

            <p class="text-gray-700 leading-relaxed text-left">
                የአዲስ አበባ ከተማ አስተዳደር የፐብሊክ ሰርቪስና የሰው ሀብት ልማት ቢሮ ለአዲስ መሶብ ዲጂታል የመንግስት አገልግሎት መ/ቤት በተለያዩ የስራ መደቦች በወጣው ማስታወቂያ
                መሰረት ያመለከታችሁ እና ስማችሁ ከዚህ በታች የተዘረዘረ አመልካቾች ፈተና የሚሰጥ ስለሆነ ማንነታችሁን የሚገልፅ መታወቂያ በመያዝ በሰአቱ እንድትገኙ እናሳስባለን።
            </p>

            <div class="text-left text-gray-700 space-y-2">
                <p><strong>ቀን፡</strong> 28/06/2018 ዓ.ም</p>
                <p><strong>ሰዓት፡</strong> ከጥዋቱ 2፡30</p>
                <p><strong>ቦታ፡</strong> የአዲስ አበባ ሳይንስና ቴክኖሎጂ ዩኒቨርሲቲ (AASTU)</p>
            </div>

            <div class="pt-4">
                <p class="text-lg font-semibold text-gray-800 mb-3">
                    የስም ዝርዝር ለማየት ሊንኩን ያጫኑ
                </p>

                <a href="https://docs.google.com/spreadsheets/d/1TbWLFLTIJAgDDzvP1gWn4CxXiey9gNiSftL5dHQsTx0/edit?usp=sharing"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-block px-6 py-3 text-white font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 transition">
                    የስም ዝርዝር ሊንክ
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center space-y-4">
            <h2 class="text-2xl font-bold text-gray-900">
                የቅጥር ማስታወቂያ
            </h2>

            <p class="text-gray-700 leading-relaxed text-left">
                የአዲስ አበባ ከተማ አስተዳደር የፐብሊክ ሰርቪስና የሰው ሀብት ልማት ቢሮ ለተለያ መ/ቤቶች
                የስራ ልምድ ያላቸውን የካሜራ ማን ቀጥሮ ለማሰራት ይፈልጋል። ፍላጎት ያላችሁ እና
                መስፈርቱን የምታሟሉ ሁሉ ይህ ማስታወቂያ በአዲስ ዘመን ጋዜጣ ታትሞ ከወጣበት ቀን ጀምሮ
                ባሉ ተከታታይ 7 ቀናት መመዝገብ የምትችሉ እናሳውቃለን።
            </p>

            <div class="text-left text-gray-700 space-y-2">
                <p class="font-semibold text-gray-900">ማሳሰቢያ</p>
                <p>👉 ምዝገባ የሚካሄደው በኦንላይን በመሆኑ በአካል ቀርቦ መመዝገብ አይፈቀድም።</p>
                <p>👉 ማንኛውም ተመዝጋቢ የናሽናል አይዲ ቁጥር (FAN ወይም FCN) የሚለውን ባለ16 ዲጂታል መታወቂያ ቁጥር ሊኖረው ይገባል።</p>
                <p>👉 የዲፕሎማ እና የሌቭል ተመራቂዎች ሲኦሲ (COC) ማስረጃቸውን ማቅረብ ይጠበቅባቸዋል።</p>
            </div>

            <div class="pt-4">
                <p class="text-lg font-semibold text-gray-800 mb-3">
                    ለመመዝገብ ሊንኩን ይጫኑ
                </p>
                <a href="https://aacapsjobs.gov.et/"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-block px-6 py-3 text-white font-semibold rounded-lg bg-green-600 hover:bg-green-700 transition">
                    https://aacapsjobs.gov.et/
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
