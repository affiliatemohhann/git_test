document.addEventListener('click', function (e) {
  if (!e.target.classList.contains('tab-btn')) return;

  const section = e.target.closest('.product-tabs');
  const tabId = e.target.dataset.tab;

  section.querySelectorAll('.tab-btn').forEach(btn =>
    btn.classList.remove('active')
  );

  section.querySelectorAll('.tab-panel').forEach(panel =>
    panel.classList.remove('active')
  );

  e.target.classList.add('active');
  section.querySelector(`#${tabId}`).classList.add('active');
});