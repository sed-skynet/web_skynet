# Popup de contacto parametrizable

Pega este bloque al final de la pantalla, preferiblemente antes del cierre de `</body>` o en un bloque HTML del editor.

Cambia solo el objeto `window.popupContactoConfig` para adaptar textos, logo, enlace, tiempos y accion del formulario en cada pantalla.

## Variables principales

| Variable | Uso |
| --- | --- |
| `logoSrc` | URL del logo que aparece arriba del popup. |
| `titleHtml` | Titulo principal. Permite usar `<span>` para resaltar parte del texto. |
| `subtitle` | Texto descriptivo bajo el titulo. |
| `phonePlaceholder` | Placeholder del campo telefono. |
| `legalText` | Primer texto legal. |
| `privacyPrefix` | Texto antes del enlace legal. |
| `privacyHref` | URL de la politica de privacidad. Usa `/politica-de-privacidad/` si debe ir a la raiz del dominio. |
| `privacyLabel` | Texto visible del enlace legal. |
| `buttonText` | Texto del boton. |
| `autoOpenDelay` | Tiempo en milisegundos antes de abrir el popup. |
| `formAction` | URL a la que enviar el formulario. Si queda vacio, no envia y solo evita el recargo. |

## Codigo listo para pegar

```html
<script>
    window.popupContactoConfig = {
        id: "popup-contacto",
        logoSrc: "https://skynet-sys.es/wp-content/uploads/2026/04/cropped-Skynet-1.jpg",
        logoAlt: "Skynet Logo",
        titleHtml: "&iquest;Quieres atenci&oacute;n <span>personalizada y r&aacute;pida</span>?",
        subtitle: "Deja tu tel&eacute;fono y uno de nuestros expertos te contactar&aacute; en la pr&oacute;xima hora.",
        phonePlaceholder: "Tel&eacute;fono",
        phoneName: "telefono",
        legalText: "Al proporcionar su n&uacute;mero de tel&eacute;fono, consiente que lo utilicemos &uacute;nicamente para contactarle.",
        privacyPrefix: "Puede consultar mas detalles en nuestra ",
        privacyHref: "politica-de-privacidad/",
        privacyLabel: "Pol&iacute;tica de Privacidad.",
        buttonText: "Solicitar Informaci&oacute;n",
        autoOpenDelay: 1000,
        formAction: "",
        formMethod: "post"
    };
</script>

<!-- POPUP CONTACTO -->
<div id="popup-contacto" class="popup-overlay" aria-hidden="true">
    <div class="popup-box" role="dialog" aria-modal="true" aria-labelledby="popup-contacto-title">
        <button class="popup-close" type="button" aria-label="Cerrar popup" data-popup-close>&times;</button>

        <div class="popup-content">
            <img class="popup-logo" data-popup-logo alt="" />

            <div class="popup-copy">
                <h2 class="popup-title" id="popup-contacto-title" data-popup-title></h2>
                <p class="popup-subtitle" data-popup-subtitle></p>
            </div>

            <form class="popup-form" data-popup-form>
                <input type="tel" class="popup-input" data-popup-phone />

                <p class="popup-legal" data-popup-legal></p>
                <p class="popup-legal">
                    <span data-popup-privacy-prefix></span><a data-popup-privacy-link></a>
                </p>

                <button class="popup-btn" type="submit" data-popup-button></button>
            </form>
        </div>
    </div>
</div>

<style>
    .popup-overlay {
        --popup-bg-start: rgba(31, 92, 128, 0.94);
        --popup-bg-end: rgba(38, 118, 162, 0.9);
        --popup-text: #f7fbff;
        --popup-muted: rgba(221, 241, 255, 0.72);
        --popup-link: #57c4ff;
        --popup-button-start: #57c4ff;
        --popup-button-end: #2563eb;

        position: fixed;
        top: 64%;
        left: 20px;
        transform: translate(-115%, -50%);
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.45s ease;
    }

    .popup-overlay.active {
        transform: translate(0, -50%);
        opacity: 1;
        pointer-events: all;
    }

    .popup-box {
        width: min(760px, calc(100vw - 48px));
        padding: 1.35rem 1.4rem 1.25rem;
        border-radius: 10px;
        position: relative;
        background: linear-gradient(180deg, var(--popup-bg-start), var(--popup-bg-end));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(186, 230, 253, 0.18);
        box-shadow: 0 18px 46px rgba(14, 44, 73, 0.26);
    }

    .popup-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        text-align: center;
    }

    .popup-logo {
        width: 110px;
        height: 124px;
        object-fit: cover;
        border-radius: 50%;
        margin-top: -5rem;
        margin-bottom: 0.6rem;
        background: #ffffff;
        border: 6px solid var(--popup-bg-start);
        box-shadow: 0 8px 18px rgba(14, 44, 73, 0.18);
        clip-path: circle(50%);
        transform: scale(1.05);
        transform-origin: center;
    }

    .popup-copy {
        width: 100%;
        max-width: 640px;
        padding: 0;
    }

    .popup-title {
        margin: 0 0 0.45rem;
        font-size: clamp(1.75rem, 2.8vw, 2.3rem);
        line-height: 1.02;
        font-weight: 800;
        color: var(--popup-text);
        text-wrap: balance;
    }

    .popup-title span {
        color: #b9ecff;
    }

    .popup-subtitle {
        margin: 0 auto 1rem;
        max-width: 620px;
        font-size: 0.96rem;
        line-height: 1.5;
        color: rgba(235, 247, 255, 0.88);
    }

    .popup-form {
        width: 100%;
        max-width: 680px;
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
    }

    .popup-input {
        display: block !important;
        width: 100% !important;
        min-width: 100% !important;
        height: 56px !important;
        padding: 0 1.2rem !important;
        border-radius: 12px !important;
        border: 1px solid rgba(186, 230, 253, 0.22) !important;
        background: rgba(14, 63, 97, 0.28) !important;
        background-image: none !important;
        color: #f8fbff !important;
        outline: none !important;
        font-size: 0.98rem !important;
        font-weight: 400 !important;
        font-family: "Inter", system-ui, sans-serif !important;
        line-height: 56px !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        box-shadow: none !important;
        text-shadow: none !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .popup-input::-webkit-contacts-auto-fill-button,
    .popup-input::-webkit-credentials-auto-fill-button {
        visibility: hidden;
        display: none !important;
        pointer-events: none;
        position: absolute;
        right: 0;
    }

    .popup-input::placeholder {
        color: rgba(225, 239, 248, 0.52) !important;
        line-height: 56px !important;
        opacity: 1 !important;
        letter-spacing: 0 !important;
        font-weight: 400 !important;
        font-size: 0.95rem !important;
        font-family: "Inter", system-ui, sans-serif !important;
    }

    .popup-input:focus {
        border-color: rgba(186, 230, 253, 0.82) !important;
        box-shadow: 0 0 0 3px rgba(186, 230, 253, 0.1) !important;
    }

    .popup-input:-webkit-autofill,
    .popup-input:-webkit-autofill:hover,
    .popup-input:-webkit-autofill:focus {
        -webkit-text-fill-color: #f8fbff !important;
        -webkit-box-shadow: 0 0 0 1000px rgba(14, 63, 97, 0.28) inset !important;
        transition: background-color 9999s ease-out 0s;
        caret-color: #f8fbff;
    }

    .popup-legal {
        margin: 0;
        text-align: left;
        font-size: 0.77rem;
        line-height: 1.45;
        color: var(--popup-muted);
    }

    .popup-legal a {
        color: var(--popup-link);
        font: inherit;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .popup-btn {
        width: 100%;
        padding: 0.98rem 1rem;
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, var(--popup-button-start), var(--popup-button-end));
        color: #ffffff;
        font-weight: 700;
        font-size: 0.98rem;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.24), inset 0 1px 0 rgba(255, 255, 255, 0.22);
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .popup-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.24);
        filter: brightness(1.03);
    }

    .popup-close {
        position: absolute;
        top: -18px;
        right: 19px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 18px;
        background: rgba(255, 255, 255, 0.06);
        border: 0;
        color: rgba(247, 251, 255, 0.88);
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .popup-close:hover {
        transform: rotate(90deg);
        background: rgba(255, 255, 255, 0.12);
    }

    @media (max-width: 900px) {
        .popup-overlay {
            top: auto;
            bottom: 24px;
            left: 16px;
            right: 16px;
            transform: translateY(120%);
        }

        .popup-overlay.active {
            transform: translateY(0);
        }

        .popup-box {
            width: min(100%, 720px);
            margin: 0 auto;
            padding: 1.35rem 1rem 1rem;
        }

        .popup-content {
            text-align: left;
        }
    }

    @media (max-width: 520px) {
        .popup-box {
            padding: 0.9rem;
        }

        .popup-logo {
            width: 100px;
            height: 100px;
            margin-top: -4rem;
        }

        .popup-title {
            font-size: 1.7rem;
        }

        .popup-subtitle {
            font-size: 0.84rem;
        }

        .popup-input,
        .popup-btn {
            font-size: 0.92rem;
        }
    }
</style>

<script>
    (function () {
        var defaults = {
            id: "popup-contacto",
            logoSrc: "",
            logoAlt: "",
            titleHtml: "",
            subtitle: "",
            phonePlaceholder: "Telefono",
            phoneName: "telefono",
            legalText: "",
            privacyPrefix: "",
            privacyHref: "",
            privacyLabel: "",
            buttonText: "Enviar",
            autoOpenDelay: 1000,
            formAction: "",
            formMethod: "post"
        };

        var customConfig = window.popupContactoConfig || {};
        var config = {};

        Object.keys(defaults).forEach(function (key) {
            config[key] = customConfig[key] !== undefined ? customConfig[key] : defaults[key];
        });

        var popup = document.getElementById(config.id);
        if (!popup) return;

        var form = popup.querySelector("[data-popup-form]");
        var logo = popup.querySelector("[data-popup-logo]");
        var title = popup.querySelector("[data-popup-title]");
        var subtitle = popup.querySelector("[data-popup-subtitle]");
        var phone = popup.querySelector("[data-popup-phone]");
        var legal = popup.querySelector("[data-popup-legal]");
        var privacyPrefix = popup.querySelector("[data-popup-privacy-prefix]");
        var privacyLink = popup.querySelector("[data-popup-privacy-link]");
        var button = popup.querySelector("[data-popup-button]");
        var closeButton = popup.querySelector("[data-popup-close]");

        if (logo) {
            logo.src = config.logoSrc;
            logo.alt = config.logoAlt;
        }

        if (title) title.innerHTML = config.titleHtml;
        if (subtitle) subtitle.innerHTML = config.subtitle;
        if (phone) {
            phone.placeholder = config.phonePlaceholder;
            phone.name = config.phoneName;
        }
        if (legal) legal.innerHTML = config.legalText;
        if (privacyPrefix) privacyPrefix.innerHTML = config.privacyPrefix;
        if (privacyLink) {
            privacyLink.href = config.privacyHref;
            privacyLink.innerHTML = config.privacyLabel;
        }
        if (button) button.innerHTML = config.buttonText;

        if (form) {
            if (config.formAction) {
                form.action = config.formAction;
                form.method = config.formMethod;
            }

            form.addEventListener("submit", function (event) {
                if (!config.formAction) {
                    event.preventDefault();
                }
            });
        }

        function openPopupContacto() {
            popup.classList.add("active");
            popup.setAttribute("aria-hidden", "false");
        }

        function closePopupContacto() {
            popup.classList.remove("active");
            popup.setAttribute("aria-hidden", "true");
        }

        if (closeButton) {
            closeButton.addEventListener("click", closePopupContacto);
        }

        window.openPopupContacto = openPopupContacto;
        window.closePopupContacto = closePopupContacto;

        if (Number(config.autoOpenDelay) >= 0) {
            setTimeout(openPopupContacto, Number(config.autoOpenDelay));
        }
    }());
</script>
```
