document.addEventListener('DOMContentLoaded', function () {
  const flash = document.querySelector('.alert');
  if (flash) {
    setTimeout(() => {
      flash.style.opacity = '0';
      flash.style.transition = 'opacity .4s ease';
    }, 4000);
  }
});
