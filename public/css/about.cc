/* ====== ABOUT PAGE ====== */
:root {
  --blue-top: #0a1853;     /* bar judul (gelap) */
  --blue-menu: #083a86;    /* bar menu  (lebih terang) */
  --accent: #1e63ff;
  --text: #0b1220;
  --muted: #4b5563;
  --bg: #f1f5f9;
  --footer: #0a1853;
  --footer-text: #ffffff;
}

/* Reset sederhana */
html, body {
  margin: 0;
  padding: 0;
  font: 15px/1.6 "Helvetica Neue", Arial, sans-serif;
  color: var(--text);
  background: var(--bg);
}

/* Kontainer isi */
.container {
  max-width: 1000px;
  margin: 40px auto;
  padding: 20px;
}

/* Card putih untuk konten */
.content {
  background: #fff;
  border-radius: 8px;
  padding: 30px 28px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Judul */
.content h1 {
  font-size: 22px;
  font-weight: 700;
  color: var(--blue-menu);
  margin-bottom: 18px;
}

/* Paragraf */
.content p {
  margin: 14px 0;
  font-size: 16px;
  color: var(--text);
  line-height: 1.7;
  text-align: justify;
}

/* Footer */
footer {
  margin-top: 40px;
  background: var(--footer);
  color: var(--footer-text);
  text-align: center;
  padding: 16px 10px;
  font-size: 14px;
  border-top: 3px solid var(--blue-menu);
}
