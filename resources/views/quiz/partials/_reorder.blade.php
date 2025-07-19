<p class="text-sm text-center text-gray-500 mb-4">Urutkan item berikut dengan memberikan peringkat 1 sampai 4.</p>
<template x-for="(choice, choiceIndex) in question.choices" :key="choice.id">
    <div class="bg-[#FFE2D6] p-4 rounded-xl shadow-md border-2 brand-border flex items-center space-x-4">
        <input 
            type="number" min="1" :max="question.choices.length"
            @input="selectReorderAnswer(question.id, choice.id, $event.target.value)"
            :value="getReorderValue(question.id, choice.id)"
            class="w-16 text-center font-bold text-lg p-2 border-2 border-gray-300 rounded-lg focus:ring-brand-pink-dark focus:border-brand-pink-dark"
        >
        <p class="text-lg font-semibold" x-text="choice.choice_text"></p>
    </div>
</template>