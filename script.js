(() => {

    const DESIGN_LAYOUT_WIDTH = 1920;

    const DEFAULT_DESIGN_HEIGHT = 1112;

    const MIN_LAYOUT_VIEWPORT = 320;

    const layoutScaleState = { rafId: null };



    function readDesignHeight(root) {
        const bodyDeclared = parseFloat(getComputedStyle(document.body).getPropertyValue('--design-height'));
        if (Number.isFinite(bodyDeclared) && bodyDeclared > 0) {
            return bodyDeclared;
        }

        const declared = parseFloat(getComputedStyle(root).getPropertyValue('--design-height'));
        return Number.isFinite(declared) && declared > 0 ? declared : DEFAULT_DESIGN_HEIGHT;
    }



    function applyLayoutScale() {

        const root = document.documentElement;

        const designHeight = readDesignHeight(root);

        const viewportWidth = Math.max(window.innerWidth, MIN_LAYOUT_VIEWPORT);

        const viewportHeight = Math.max(window.innerHeight || 0, 1);



        const widthScale = viewportWidth / DESIGN_LAYOUT_WIDTH;

        const heightScale = viewportHeight / designHeight;

        const scale = Math.min(1, widthScale, heightScale);



        const scaledWidth = DESIGN_LAYOUT_WIDTH * scale;

        const scaledHeight = designHeight * scale;

        const offsetX = Math.max(0, (viewportWidth - scaledWidth) / 2);

        const offsetY = Math.max(0, (viewportHeight - scaledHeight) / 2);



        root.style.setProperty('--layout-scale', scale.toFixed(4));

        root.style.setProperty('--layout-offset-x', `${offsetX}px`);

        root.style.setProperty('--layout-offset-y', `${offsetY}px`);

    }



    function scheduleLayoutScaleUpdate() {

        if (layoutScaleState.rafId !== null) return;

        layoutScaleState.rafId = window.requestAnimationFrame(() => {

            layoutScaleState.rafId = null;

            applyLayoutScale();

        });

    }



    function initAdaptiveLayoutScaling() {

        if (window.__layoutScaleInitialized) {

            applyLayoutScale();

            return;

        }



        window.__layoutScaleInitialized = true;

        applyLayoutScale();

        window.addEventListener('resize', scheduleLayoutScaleUpdate);

        window.addEventListener('orientationchange', scheduleLayoutScaleUpdate);

        window.addEventListener('pageshow', (event) => {

            if (event.persisted) {

                applyLayoutScale();

            }

        });

        window.addEventListener('load', applyLayoutScale);

        document.addEventListener('DOMContentLoaded', applyLayoutScale);

    }



    initAdaptiveLayoutScaling();

})();












function handleUserAccountClick() {
    if (window.IS_AUTH) {
        window.location.href = 'profile.php';
    } else {
        window.location.href = 'login.php';
    }
}

function scrollDown() {
    const target = document.querySelector('#why-us');
    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    } else {
        window.scrollBy({
            top: window.innerHeight,
            behavior: 'smooth'
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const navButtons = document.querySelectorAll('.nav-button');

    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            navButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const targetSel = this.getAttribute('data-target');
            if (targetSel) {
                const section = document.querySelector(targetSel);
                if (section) section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            const page = this.getAttribute('data-page');
            if (page) {
                handleNavigation(page);
            }
        });
    });

    initializeGoodsPage();
    initializeFoodPage();
});

window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const speedElements = document.querySelectorAll('.speed-text');
    speedElements.forEach(element => {
        const speed = 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

function openLoginModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
      <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h2>Вход в личный кабинет</h2>
          <form>
              <input type="email" placeholder="Email" required>
              <input type="password" placeholder="Пароль" required>
              <button type="submit">Войти</button>
          </form>
      </div>
  `;

    const modalStyles = `
      .modal { display:block; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); }
      .modal-content { background-color:#fefefe; margin:15% auto; padding:20px; border-radius:10px; width:300px; text-align:center; }
      .close { color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer; }
      .close:hover { color:black; }
      .modal input { width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px; }
      .modal button { width:100%; padding:10px; background-color:#650e5c; color:#fff; border:none; border-radius:5px; cursor:pointer; }
  `;

    const style = document.createElement('style');
    style.textContent = modalStyles;
    document.head.appendChild(style);
    document.body.appendChild(modal);
}

function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) modal.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
});

function initializeGoodsPage() {
    const productCategories = document.querySelectorAll('.product-category');
    if (productCategories.length === 0) return;

    productCategories.forEach(category => {
        category.addEventListener('click', function() {
            const categoryType = this.getAttribute('data-category');
            console.log(`Opening category: ${categoryType}`);
            handleCategoryClick(categoryType);
        });

        category.setAttribute('tabindex', '0');
    });

    addAccessibilityStyles();
}

function initializeFoodPage() {
    const productItems = document.querySelectorAll('.product-item');
    if (productItems.length === 0) return;
    const navButtons = document.querySelectorAll('.nav-button');

    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            navButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });

    productItems.forEach(item => {
        item.addEventListener('click', function() {
            const productName = this.querySelector('.product-name').textContent;
            const productPrice = this.querySelector('.product-price').textContent;

            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });

        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(255, 255, 255, 0.05)';
            this.style.borderRadius = '12px';
            this.style.transition = 'all 0.3s ease';
        });

        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    });
}

function handleNavigation(page) {
    switch (page) {
        case 'home':
            window.location.href = 'index.php';
            break;
        case 'products':
        case 'goods':
            window.location.href = 'goods.php';
            break;
        case 'support':
            window.location.href = 'support.php';
            break;
        case 'contacts':
            window.location.href = 'contacts.php';
            break;
        default:
            window.location.href = 'index.php';
            break;
    }
}

function handleCategoryClick(category) {
    switch (category) {
        case 'consoles':
            window.location.href = 'consoles.php';
            break;
        case 'food':
            window.location.href = 'food.php';
            break;
        case 'pc':
            window.location.href = 'pc.php';
            break;
        default:
            window.location.href = 'consoles.php';
            break;
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' || event.key === ' ') {
        const focusedElement = document.activeElement;
        if (focusedElement.classList.contains('nav-button') ||
            focusedElement.classList.contains('product-category') ||
            focusedElement.classList.contains('user-account')) {
            event.preventDefault();
            focusedElement.click();
        }
    }
});

function addAccessibilityStyles() {
    const style = document.createElement('style');
    style.textContent = `
    .nav-button:focus,
    .user-account:focus {
      outline: 2px solid #650e5c;
      outline-offset: 2px;
    }
    
    .product-category:focus {
      outline: none;
    }
    
    .product-category {
      tabindex: 0;
    }
  `;
    document.head.appendChild(style);

    const userAccount = document.querySelector('.user-account');
    if (userAccount) {
        userAccount.setAttribute('tabindex', '0');
    }
}

function showOnMap() {
    window.open("https://yandex.kz/maps/ru/org/ded_insayd/125664579292", '_blank');
}

function openSocialLink(platform) {
    const links = {
        telegram: 'https://t.me/https://yandex.kz/maps/ru/org/ded_insayd/125664579292',
        instagram: 'https://yandex.kz/maps/ru/org/ded_insayd/125664579292',
        youtube: 'https://yandex.kz/maps/ru/org/ded_insayd/125664579292',
        facebook: 'https://yandex.kz/maps/ru/org/ded_insayd/125664579292'
    };

    if (links[platform]) {
        window.open(links[platform], '_blank');
    }
}