<div class="bg-white p-6 rounded-2xl shadow-lg border-2 brand-border">
    <input 
        type="text" 
        :value="userAnswers[question.id] || ''"
        @input="selectAnswer(question.id, $event.target.value)"
        placeholder="Ketik jawabanmu di sini..."
        class="w-full text-2xl font-semibold bg-transparent focus:outline-none"
    >
</div>