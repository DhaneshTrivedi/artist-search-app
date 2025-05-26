const input = document.getElementById('searchInput');
const resultsBox = document.getElementById('results');
let timeout;

input.addEventListener('input', () => {
  clearTimeout(timeout);
  const query = input.value.trim();

  if (!query) {
    resultsBox.innerHTML = '';
    return;
  }

  resultsBox.innerHTML = '<p>Searching...</p>';

  timeout = setTimeout(() => {
    fetch(`/artist-search-app/api/search.php?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        resultsBox.innerHTML = '';

        if (data.length === 0) {
          resultsBox.innerHTML = '<p>No matches found.</p>';
        } else {
          data.forEach(artist => {
            const div = document.createElement('div');
            div.classList.add('artist');
            div.innerHTML = `
              <img src="${artist.profile_picture}" alt="${artist.name}" width="50"/>
              <div>
                <strong>${highlightMatch(artist.name, query)}</strong><br>
                Genre: ${artist.genre}<br>
                Location: ${artist.location}
              </div>
            `;
            resultsBox.appendChild(div);
          });
        }
      });
  }, 300);
});

// Highlights matching part of text
function highlightMatch(text, query) {
  const re = new RegExp(`(${query})`, 'i');
  return text.replace(re, `<mark>$1</mark>`);
}
