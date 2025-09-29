fetch('/vstr-lggr/logger.php', { 
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    pageUrl: window.location.href
  })
});
