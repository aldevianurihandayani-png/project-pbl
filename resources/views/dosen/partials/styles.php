<style>
    :root{
      --navy:#0b1d54; --navy-2:#0e257a; --bg:#f5f7fb; --card:#ffffff;
      --muted:#6c7a8a; --ring:rgba(13,23,84,.10);
      --shadow:0 6px 20px rgba(13,23,84,.08); --radius:16px;
    }
    *{ box-sizing:border-box }
    body{
      margin:0;
      font-family:Arial,Helvetica,sans-serif;
      background:var(--bg);
      display:grid;
      grid-template-columns:260px 1fr;
      min-height:100vh;
    }

    main{ display:flex; flex-direction:column; min-width:0; }

    .page{ padding:26px; display:grid; gap:18px; }

    /* KPI */
    .kpi{ display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:16px; }
    .kpi .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:16px 18px;
      display:flex;
      align-items:center;
      gap:12px;
      border:1px solid var(--ring);
    }
    .kpi .icon{
      width:36px; height:36px;
      border-radius:10px;
      background:#eef3ff;
      display:grid; place-items:center;
      color:var(--navy-2);
    }
    .kpi .meta small{ color:var(--muted); }
    .kpi .meta b{ font-size:22px; color:var(--navy-2); }

    /* Card umum */
    .card{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      border:1px solid var(--ring);
    }
    .card-hd{
      padding:14px 18px;
      border-bottom:1px solid #eef1f6;
      display:flex; align-items:center; gap:10px;
      color:var(--navy-2);
      font-weight:700;
    }
    .card-bd{ padding:16px 18px; color:#233042; }
    .muted{ color:var(--muted); }

    /* Responsive: body jadi 1 kolom, sidebar slide */
    @media (max-width: 980px){
      body{ grid-template-columns:1fr; }
    }
  </style>
</head>