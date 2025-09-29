// Convert U+1F1FA U+1F1F8 
function unicodeToEmoji(unicodeStr) {
  try {
    return unicodeStr
      .split(' ')
      .map(part => String.fromCodePoint(parseInt(part.replace('U+', ''), 16)))
      .join('');
  } catch {
    return unicodeStr;
  }
}

// Fetch visitors from logger.php
async function fetchVisitors(startDate, endDate) {
  const params = new URLSearchParams({ startDate, endDate });

  return fetch(`/vstr-lggr/logger.php?${params.toString()}`)
    .then(response => {
      if (!response.ok) throw new Error(`Server error: ${response.status}`);
      return response.json();
    });
}

function clean() {
  document.querySelectorAll('.total-results').forEach(el => {
    el.classList.add('hide');
  });
}

// Render table from data
function loadVisitorTable() {
  clean();
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;
  const resultsDiv = document.getElementById('results');

  if (!startDate || !endDate) {
    resultsDiv.innerHTML = '⚠️ Please select both dates.';
    return;
  }

  resultsDiv.innerHTML = 'Loading...';

  fetchVisitors(startDate, endDate)
    .then(data => {
      if (data.status !== 'success') {
        resultsDiv.innerHTML = `${data.message || 'Unexpected error'}`;
        return;
      }

      if (data.data.length === 0) {
        resultsDiv.innerHTML = 'No records found.';
        return;
      }

      const table = document.createElement('table');
      table.className = 'table';
      const headers = Object.keys(data.data[0]);

      // Table header
      const thead = document.createElement('thead');
      const headRow = document.createElement('tr');
      headers.forEach(key => {
        const th = document.createElement('th');
        th.textContent = key;
        headRow.appendChild(th);
      });
      thead.appendChild(headRow);
      table.appendChild(thead);

      // Table body
      const tbody = document.createElement('tbody');
      data.data.forEach(row => {
        const tr = document.createElement('tr');
        headers.forEach(key => {
          const td = document.createElement('td');
          if (key === 'flag') {
            td.className = 'flag';
            td.textContent = unicodeToEmoji(row[key]);
          } else {
            td.textContent = row[key];
          }
          tr.appendChild(td);
        });
        tbody.appendChild(tr);
      });
      table.appendChild(tbody);

      // Output
      resultsDiv.innerHTML = '';
      resultsDiv.appendChild(table);
      summarizeVisitorData();
    })
    .catch(err => {
      resultsDiv.innerHTML = `Fetch error: ${err.message}`;
    });

}

function setDateRange(days) {
  const today = new Date();
  const last = new Date();

  // Set the date 7 days ago
  last.setDate(today.getDate() - days);

  // Format dates to YYYY-MM-DD for the input fields
  const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  // Set the value of the date inputs
  document.getElementById('startDate').value = formatDate(last);
  document.getElementById('endDate').value = formatDate(today);
}

function summarizeVisitorData() {
  const resultsDiv = document.getElementById('results');
  const table = resultsDiv.querySelector('table');
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;

  if (!table) {
    console.warn("No table found.");
    return "No data available.";
  }

  const tbodyRows = table.querySelectorAll('tbody tr');
  const visitorCount = tbodyRows.length;

  let requestCount = 0;
  tbodyRows.forEach(row => {
    const cells = row.querySelectorAll('td');
    for (let i = 0; i < cells.length; i++) {
      const header = table.querySelectorAll('th')[i].textContent;
      if (header === 'request_cnt') {
        const value = parseInt(cells[i].textContent) || 0;
        requestCount += value;
      }
    }
  });

  // Set summary
  const newContent = `Unique Visitors: ${visitorCount} | Total Requests: ${requestCount} | ${startDate} - ${endDate}`;
  document.querySelectorAll('.total-results').forEach(el => {
    el.innerHTML = newContent;
  });

  if (requestCount > 0) {
    document.querySelectorAll('.total-results').forEach(el => {
     el.classList.remove('hide');
    });
  }
}



  // Initialization of the panel
  function init() {
    setDateRange(7); // show last 7 days 
    loadVisitorTable();
  }
  init();