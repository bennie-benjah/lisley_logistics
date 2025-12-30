window.initFAQ = function () {
  faqQuestions.forEach(question => {
    question.addEventListener('click', () => {
      question.classList.toggle('active');
      question.nextElementSibling.classList.toggle('active');
    });
  });
};
