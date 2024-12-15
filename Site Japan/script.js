document.addEventListener('DOMContentLoaded', () => {
  const destinations = document.querySelectorAll('.destination');

  destinations.forEach(destination => {
    destination.addEventListener('mouseenter', () => {
      destination.style.transform = 'scale(1.05)';
      destination.style.transition = 'transform 0.3s ease-in-out';
    });

    destination.addEventListener('mouseleave', () => {
      destination.style.transform = 'scale(1)';
    });

    destination.addEventListener('click', () => {
      const description = destination.querySelector('p');
      if (description.style.display === 'none') {
        description.style.display = 'block';
        destination.style.minHeight = '400px';
      } else {
        description.style.display = 'none';
        destination.style.minHeight = '300px';
      }
    });
  });
});

