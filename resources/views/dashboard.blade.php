<!doctype html>
<html lang="bs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kupatonska oprema</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://images.unsplash.com">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #1e3e65;
            --blue: #2865a5;
            --paper: #f5f5f2;
            --muted: #78828d;
            --line: #e3e4e1;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; }

        .site-header { position: sticky; z-index: 5; top: 0; display: flex; align-items: center; justify-content: center; height: 60px; border-bottom: 1px solid var(--line); background: #fff; }
        .site-title { margin: 0; color: #26333d; font-size: 15px; font-weight: 700; letter-spacing: .03em; }
        .menu-toggle, .cart-button, .logout-button { position: relative; display: grid; place-items: center; width: 36px; height: 36px; padding: 0; border: 0; background: none; color: #38434c; cursor: pointer; }
        .menu-toggle { position: absolute; left: 10px; }
        .cart-button { position: absolute; right: 15px; }
        .menu-toggle svg, .cart-button svg, .logout-button svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; }
        .menu-panel { position: absolute; top: 53px; left: 10px; width: 190px; padding: 8px; border: 1px solid var(--line); border-radius: 4px; background: #fff; box-shadow: 0 8px 24px rgba(20, 34, 48, .12); }
        .menu-panel[hidden] { display: none; }
        .menu-panel a, .menu-panel button { display: block; width: 100%; padding: 11px 12px; border: 0; border-radius: 3px; background: transparent; color: var(--ink); text-align: left; font-size: 13px; cursor: pointer; }
        .menu-panel a:hover, .menu-panel button:hover { background: #f1f5f8; }
        .cart-count { position: absolute; top: -1px; right: -7px; display: grid; place-items: center; min-width: 16px; height: 16px; padding: 0 4px; border-radius: 50%; background: var(--blue); color: white; font-size: 10px; font-weight: 700; }
        .order-confirmation { width: min(100% - 32px, 1100px); margin: 14px auto 0; padding: 13px 16px; border: 1px solid #bad7c0; border-radius: 3px; background: #edf7ef; color: #27623a; font-size: 13px; }

        .catalog { width: min(100% - 32px, 1100px); margin: 14px auto 48px; }
        .product-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
        .product-card { min-width: 0; overflow: hidden; border: 1px solid #e1e2df; border-radius: 3px; background: #fff; box-shadow: 0 3px 13px rgba(27, 43, 57, .06); }
        .product-image { position: relative; display: grid; place-items: center; height: 240px; overflow: hidden; background: #e3eaf3; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
        .product-card:hover .product-image img { transform: scale(1.025); }
        .product-badge { position: absolute; top: 11px; left: 11px; padding: 5px 9px; border-radius: 3px; background: var(--blue); color: white; font-size: 9px; font-weight: 700; letter-spacing: .08em; }
        .product-info { padding: 17px 18px 18px; }
        .product-info h3 { margin: 0 0 7px; color: var(--ink); font: 600 14px/1.4 'Playfair Display', serif; }
        .product-description { min-height: 59px; margin: 0; color: #818991; font-size: 12px; line-height: 1.65; }
        .product-bottom { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 16px; }
        .price { color: var(--ink); font-size: 11px; font-weight: 600; }
        .discount {
    display: block;
    margin-top: 4px;
    color: #78828d;
    font-size: 10px;
    font-weight: 500;
}
        .add-button { min-height: 36px; padding: 0 13px; border: 0; border-radius: 3px; background: var(--ink); color: white; font-size: 10px; font-weight: 700; cursor: pointer; transition: background .2s ease; }
        .add-button:hover { background: var(--blue); }

        .cart-dialog { width: min(420px, calc(100% - 32px)); max-height: min(80vh, 620px); padding: 0; border: 1px solid var(--line); border-radius: 4px; color: var(--ink); box-shadow: 0 20px 70px rgba(20, 34, 48, .2); }
        .cart-dialog::backdrop { background: rgba(20, 34, 48, .34); }
        .cart-head { display: flex; align-items: center; justify-content: space-between; padding: 19px 20px; border-bottom: 1px solid var(--line); }
        .cart-head h2 { margin: 0; font: 600 20px 'Playfair Display', serif; }
        .cart-close { width: 30px; height: 30px; border: 0; background: transparent; color: var(--ink); font-size: 23px; cursor: pointer; }
        .cart-content { padding: 20px; }
        .cart-empty { margin: 0; color: var(--muted); font-size: 13px; }
        .cart-items { display: grid; gap: 12px; margin: 0; padding: 0; list-style: none; }
        .cart-items li { display: flex; justify-content: space-between; gap: 14px; padding-bottom: 12px; border-bottom: 1px solid var(--line); font-size: 12px; line-height: 1.5; }
        .cart-items small { color: var(--muted); white-space: nowrap; }
        .order-form { display: grid; gap: 12px; margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); }
        .order-form h3 { margin: 0; color: var(--ink); font: 600 17px 'Playfair Display', serif; }
        .order-field { display: grid; gap: 5px; color: #59636d; font-size: 11px; font-weight: 600; }
        .order-field input { width: 100%; min-height: 40px; padding: 8px 10px; border: 1px solid #d7dce1; border-radius: 3px; background: #fff; color: #283744; font: 400 13px 'DM Sans', sans-serif; }
        .order-field input:focus { outline: 2px solid rgba(40, 101, 165, .22); border-color: var(--blue); }
        .order-submit { min-height: 42px; border: 0; border-radius: 3px; background: var(--ink); color: white; font-size: 12px; font-weight: 700; cursor: pointer; }
        .order-submit:hover { background: var(--blue); }
        .order-errors { margin: 0; color: #aa3027; font-size: 12px; line-height: 1.5; }
        .order-note { margin: 0; color: var(--muted); font-size: 11px; line-height: 1.5; }

        @media (max-width: 760px) {
            .catalog { width: min(100% - 40px, 520px); margin-top: 14px; }
            .product-grid { grid-template-columns: 1fr; gap: 18px; }
            .product-image { height: clamp(210px, 58vw, 270px); }
            .product-info { padding: 17px 18px 18px; }
        }
        @media (max-width: 520px) {
            .catalog { width: calc(100% - 40px); margin-top: 14px; }
            .product-grid { gap: 18px; }
            .product-image { height: 220px; }
            .product-description { min-height: 0; }
            .product-bottom { margin-top: 14px; }
            .add-button { min-height: 40px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <button class="menu-toggle" id="menu-toggle" type="button" aria-label="Otvori meni" aria-expanded="false" aria-controls="site-menu">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="site-title">AI 3D PRINT</h1>
        <button class="cart-button" id="cart-open" type="button" aria-label="Otvori korpu" title="Otvori korpu">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
            <span class="cart-count" id="cart-count" aria-live="polite">0</span>
        </button>
        <nav class="menu-panel" id="site-menu" hidden>
            <a href="#proizvodi" id="menu-products">Proizvodi</a>
            <button type="button" id="menu-cart">Otvori korpu</button>
        </nav>
    </header>

    @if (session('order_success'))
        <div class="order-confirmation" role="status">{{ session('order_success') }}</div>
    @endif

    <main>
        <section class="catalog" id="proizvodi">
            <div class="product-grid">
                <article class="product-card" data-product-id="wc-tipka-4u1" data-product="WC Tipka za Ispiranje - 4u1 Set">
                    <div class="product-image">
                        <img src="images/slika1.jpg" alt="Uređeno kupatilo s modernom sanitarnom opremom" loading="lazy">
                        <span class="product-badge">KOMPLET</span>
                    </div>
                    <div class="product-info">
                        <h3>Plastična baza za tipkalo</h3>
                        <p class="product-description">Kompletan set za montažu WC tipke. Uključuje plastičnu bazu, vodootporni samoljepljivi papir, nosače i serafe.</p>
                        <div class="product-bottom">
                        <div>
                            <span class="price">100KM</span>
                            <span class="discount">Za narucene 4 baze popust 10%</span>
                        </div>
                        <button class="add-button" type="button">Dodaj u korpu</button>
                    </div>
                    </div>
                </article>
                

            

                <article class="product-card" data-product-id="tece-tipka-bijela" data-product="TECE WC Tipka - Bijela">
                    <div class="product-image">
                        <img src="images/slika3vjesalica.jpg" alt="Bijelo kupatilo s čistim, minimalističkim detaljima" loading="lazy">
                    </div>
                    <div class="product-info">
                        <h3>Skrivene INOX vješalice za ručnike</h3>
                        <p class="product-description">Kompletan set </p>
                        <div class="product-bottom">
                            <span class="price">40KM</span>
                            <button class="add-button" type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </article>



                <article class="product-card" data-product-id="tece-tipka-bijela" data-product="TECE WC Tipka - Bijela">
                    <div class="product-image">
                        <img src="images/slika3vjesalica.jpg" alt="Bijelo kupatilo s čistim, minimalističkim detaljima" loading="lazy">
                    </div>
                    <div class="product-info">
                        <h3>Revizije</h3>
                        <p class="product-description">Kompletan set </p>
                        <div class="product-bottom">
                            <span class="price">40KM</span>
                            <span class="discount">Dostupno uskoro</span>
                           // <button class="add-button" type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </article>

                <article class="product-card" data-product-id="tece-tipka-bijela" data-product="TECE WC Tipka - Bijela">
                    <div class="product-image">
                        <img src="images/slika3vjesalica.jpg" alt="Bijelo kupatilo s čistim, minimalističkim detaljima" loading="lazy">
                    </div>
                    <div class="product-info">
                        <h3>Šabloni + žica za rezanje</h3>
                        <p class="product-description">Kompletan set </p>
                        <div class="product-bottom">
                            <span class="price">50KM</span>
                            <span class="discount">Dostupno uskoro</span>
                           // <button class="add-button" type="button">Dodaj u korpu</button>
                        </div>
                    </div>
                </article>
            </div>
        </section>

    </main>

    <dialog class="cart-dialog" id="cart-dialog" aria-labelledby="cart-title">
        <div class="cart-head">
            <h2 id="cart-title">Vaša korpa</h2>
            <button class="cart-close" id="cart-close" type="button" aria-label="Zatvori korpu">&times;</button>
        </div>
        <div class="cart-content">
            <p class="cart-empty" id="cart-empty">Korpa je trenutno prazna.</p>
            <ul class="cart-items" id="cart-items"></ul>
            <form class="order-form" id="order-form" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <h3>Podaci za narudžbu</h3>
                @if ($errors->any())
                    <p class="order-errors" role="alert">{{ $errors->first() }}</p>
                @endif
                <input type="hidden" name="items_json" id="order-items">
                <label class="order-field" for="customer-name">
                    Ime i prezime
                    <input id="customer-name" name="customer_name" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="120">
                </label>
                <label class="order-field" for="customer-phone">
                    Telefon
                    <input id="customer-phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" autocomplete="tel" required maxlength="32">
                </label>
                <label class="order-field" for="customer-email">
                    E-mail (opcionalno)
                    <input id="customer-email" name="customer_email" type="email" value="{{ old('customer_email') }}" autocomplete="email" maxlength="255">
                </label>
                <p class="order-note">Nakon slanja ćemo vas kontaktirati radi potvrde narudžbe.</p>
                <button class="order-submit" type="submit">Pošalji narudžbu</button>
            </form>
        </div>
    </dialog>

    <script>
        const cartStorageKey = 'guest-order-cart-v1';
        const cartDialog = document.getElementById('cart-dialog');
        const cartItemsList = document.getElementById('cart-items');
        const cartEmptyMessage = document.getElementById('cart-empty');
        const cartCount = document.getElementById('cart-count');
        let cart = JSON.parse(localStorage.getItem(cartStorageKey) || '[]');
        if (@json(session()->has('order_success'))) {
            cart = [];
            localStorage.removeItem(cartStorageKey);
        }

        function renderCart() {
            cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
            cartItemsList.replaceChildren(...cart.map((item) => {
                const row = document.createElement('li');
                const title = document.createElement('span');
                const quantity = document.createElement('small');
                title.textContent = item.name;
                quantity.textContent = `Količina: ${item.quantity}`;
                row.append(title, quantity);
                return row;
            }));
            cartEmptyMessage.hidden = cart.length > 0;
        }

        document.querySelectorAll('.add-button').forEach((button) => {
            button.addEventListener('click', () => {
                const card = button.closest('.product-card');
                const id = card.dataset.productId;
                const name = card.dataset.product;
                const item = cart.find((entry) => entry.id === id);
                if (item) item.quantity += 1;
                else cart.push({ id, name, quantity: 1 });
                localStorage.setItem(cartStorageKey, JSON.stringify(cart));
                renderCart();
                button.textContent = 'Dodano';
                window.setTimeout(() => { button.textContent = 'Dodaj u korpu'; }, 1100);
            });
        });

        document.getElementById('cart-open').addEventListener('click', () => cartDialog.showModal());
        document.getElementById('cart-close').addEventListener('click', () => cartDialog.close());
        const menuToggle = document.getElementById('menu-toggle');
        const siteMenu = document.getElementById('site-menu');
        const closeMenu = () => {
            siteMenu.hidden = true;
            menuToggle.setAttribute('aria-expanded', 'false');
        };
        menuToggle.addEventListener('click', () => {
            siteMenu.hidden = !siteMenu.hidden;
            menuToggle.setAttribute('aria-expanded', String(!siteMenu.hidden));
        });
        document.getElementById('menu-products').addEventListener('click', closeMenu);
        document.getElementById('menu-cart').addEventListener('click', () => {
            closeMenu();
            cartDialog.showModal();
        });
        document.addEventListener('click', (event) => {
            if (!siteMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                closeMenu();
            }
        });
        document.getElementById('order-form').addEventListener('submit', (event) => {
            if (cart.length === 0) {
                event.preventDefault();
                cartEmptyMessage.textContent = 'Dodajte proizvod u korpu prije slanja narudžbe.';
                cartEmptyMessage.hidden = false;
                return;
            }
            document.getElementById('order-items').value = JSON.stringify(
                cart.map(({ id, quantity }) => ({ id, quantity }))
            );
        });
        cartDialog.addEventListener('click', (event) => {
            if (event.target === cartDialog) cartDialog.close();
        });
        renderCart();
        @if ($errors->any())
            cartDialog.showModal();
        @endif
    </script>
</body>
</html>
