<template x-for="(choice, choiceIndex) in question.choices" :key="choice.id">
    <div 
        @click="selectCheckboxAnswer(question.id, choice.id)"
        class="answer-choice bg-[#FFE2D6] p-10 rounded-3xl flex items-center space-x-5"
        :class="{ 'selected': userAnswers[question.id] && userAnswers[question.id].includes(choice.id) }"
    >
        <div class="answer-letter flex-shrink-0 rounded-lg flex items-center justify-center font-bold text-xl" x-text="String.fromCharCode(65 + choiceIndex)"></div>
        <p class="text-lg font-semibold" x-text="choice.choice_text"></p>
    </div>
</template>