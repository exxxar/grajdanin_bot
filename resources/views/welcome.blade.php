<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Гражданская Платформа</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="theme.css">
    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>


        body{
            background:
                radial-gradient(circle at top left,
                rgba(71,194,192,.08),
                transparent 30%),

                linear-gradient(
                    180deg,
                    #f8fbfc 0%,
                    #f5f7fb 100%
                );

            color:var(--color-text);
            font-family:Inter,sans-serif;

            overflow-x:hidden;
        }

        /* ========================================
           HERO
        ======================================== */

        .hero{
            min-height:100vh;
            display:flex;
            align-items:center;
            padding:90px 0;
            position:relative;
        }

        .hero-title{
            font-size:4rem;
            font-weight:900;
            line-height:1.05;
            letter-spacing:-2px;

            color:var(--color-text);
        }

        .hero-subtitle{
            font-size:1.2rem;
            color:var(--color-text-secondary);

            line-height:1.8;

            margin-top:28px;
            max-width:650px;
        }

        .feature-list{
            margin-top:35px;
        }

        .feature-list li{
            margin-bottom:18px;
            font-size:1.08rem;

            display:flex;
            align-items:center;

            color:var(--color-text);
        }

        .feature-list i{
            width:34px;
            height:34px;

            display:flex;
            align-items:center;
            justify-content:center;

            background:var(--color-primary-soft);
            color:var(--color-primary);

            border-radius:12px;

            margin-right:14px;
        }

        /* ========================================
           PHONE MOCKUP
        ======================================== */

        .phone-wrapper{
            position:relative;
            display:flex;
            justify-content:center;
        }

        .phone-wrapper::before{
            content:'';

            position:absolute;

            width:420px;
            height:420px;

            background:radial-gradient(
                circle,
                rgba(71,194,192,.22),
                transparent 70%
            );

            filter:blur(10px);
            z-index:0;
        }

        .phone-frame{
            position:relative;
            z-index:2;

            width:340px;
            height:700px;

            background:#0f172a;

            border-radius:42px;
            padding:12px;

            border:1px solid rgba(255,255,255,.08);

            box-shadow:var(--shadow-lg);
        }

        .phone-screen{
            width:100%;
            height:100%;

            border-radius:32px;
            overflow:hidden;

            background:#fff;
        }

        .phone-screen iframe{
            width:100%;
            height:100%;
            border:none;
        }

        /* ========================================
           SECTIONS
        ======================================== */

        .section{
            padding:110px 0;
            position:relative;
        }

        .section-title{
            font-size:3rem;
            font-weight:900;
            line-height:1.1;

            margin-bottom:22px;

            color:var(--color-text);
        }

        .section-subtitle{
            color:var(--color-text-secondary);
            font-size:1.1rem;
            line-height:1.8;

            margin-bottom:60px;
            max-width:720px;
        }

        /* ========================================
           CARDS
        ======================================== */

        .problem-card{
            background:rgba(255,255,255,.88);

            backdrop-filter:blur(10px);

            border-radius:28px;

            padding:32px;

            height:100%;

            transition:.25s ease;

            border:1px solid rgba(255,255,255,.65);

            box-shadow:var(--shadow-sm);
        }

        .problem-card:hover{
            transform:translateY(-8px);

            box-shadow:var(--shadow-md);

            border-color:rgba(71,194,192,.25);
        }

        .problem-icon{
            width:72px;
            height:72px;

            border-radius:22px;

            background:var(--color-primary-soft);

            display:flex;
            align-items:center;
            justify-content:center;

            color:var(--color-primary);

            font-size:30px;

            margin-bottom:24px;
        }

        .problem-card h4{
            font-weight:800;
            margin-bottom:16px;

            color:var(--color-text);
        }

        .problem-card p{
            color:var(--color-text-secondary);
            line-height:1.7;
        }

        /* ========================================
           TAGS
        ======================================== */

        .problem-tags{
            margin-top:22px;

            display:flex;
            flex-wrap:wrap;
            gap:10px;
        }

        .problem-tags span{
            background:var(--color-primary-soft);

            color:var(--color-primary-hover);

            border-radius:999px;

            padding:8px 14px;

            font-size:.88rem;
            font-weight:600;
        }

        /* ========================================
           STATS
        ======================================== */

        .stats-box{
            background:linear-gradient(
                135deg,
                var(--color-primary),
                #5dd7d5
            );

            border-radius:34px;

            padding:70px 50px;

            color:#fff;

            box-shadow:
                0 25px 60px rgba(71,194,192,.25);
        }

        .stat-number{
            font-size:3.2rem;
            font-weight:900;

            margin-bottom:10px;
        }

        /* ========================================
           BUTTONS
        ======================================== */

        .download-btn{
            border-radius:18px;

            padding:16px 30px;

            font-size:1.05rem;
            font-weight:700;

            transition:.25s ease;
        }

        .btn-primary{
            background:linear-gradient(
                135deg,
                var(--color-primary),
                #5dd7d5
            );

            border:none;

            box-shadow:
                0 12px 30px rgba(71,194,192,.25);
        }

        .btn-primary:hover{
            transform:translateY(-2px);

            background:linear-gradient(
                135deg,
                var(--color-primary-hover),
                var(--color-primary)
            );
        }

        .btn-outline-dark{
            border:1px solid var(--color-border);
            color:var(--color-text);

            background:#fff;
        }

        .btn-outline-dark:hover{
            background:var(--color-primary-soft);
            border-color:var(--color-primary);

            color:var(--color-primary-hover);
        }

        /* ========================================
           MOBILE
        ======================================== */

        @media(max-width:991px){

            .hero{
                padding:70px 0;
                text-align:center;
            }

            .hero-title{
                font-size:2.7rem;
            }

            .hero-subtitle{
                margin-left:auto;
                margin-right:auto;
            }

            .feature-list li{
                justify-content:center;
            }

            .phone-frame{
                width:300px;
                height:620px;

                margin-top:60px;
            }

            .section-title{
                font-size:2.2rem;
            }

            .stats-box{
                padding:50px 30px;
            }
        }

        .site-footer{
            position:relative;

            margin-top:120px;

            background:
                linear-gradient(
                    180deg,
                    rgba(71,194,192,.04),
                    rgba(71,194,192,.08)
                );

            border-top:1px solid rgba(71,194,192,.12);

            padding:
                90px 0 35px;
        }

        /* ========================================
           BRAND
        ======================================== */

        .footer-brand{
            display:flex;
            align-items:flex-start;
            gap:20px;
        }

        .footer-logo{
            min-width:68px;
            width:68px;
            height:68px;

            border-radius:24px;

            background:
                linear-gradient(
                    135deg,
                    var(--color-primary),
                    #5dd7d5
                );

            display:flex;
            align-items:center;
            justify-content:center;

            color:#fff;
            font-size:28px;

            box-shadow:
                0 15px 35px rgba(71,194,192,.25);
        }

        .footer-brand h4{
            font-size:1.3rem;
            font-weight:800;

            margin-bottom:12px;

            color:var(--color-text);
        }

        .footer-brand p{
            color:var(--color-text-secondary);

            line-height:1.8;
        }

        /* ========================================
           TITLES
        ======================================== */

        .footer-title{
            font-size:1.05rem;
            font-weight:800;

            margin-bottom:24px;

            color:var(--color-text);
        }

        /* ========================================
           LINKS
        ======================================== */

        .footer-links{
            list-style:none;

            padding:0;
            margin:0;
        }

        .footer-links li{
            margin-bottom:14px;
        }

        .footer-links a{
            text-decoration:none;

            color:var(--color-text-secondary);

            transition:.25s ease;
        }

        .footer-links a:hover{
            color:var(--color-primary);

            padding-left:4px;
        }

        /* ========================================
           CONTACTS
        ======================================== */

        .footer-contact{
            list-style:none;

            padding:0;
            margin:0;
        }

        .footer-contact li{
            display:flex;
            align-items:center;
            gap:12px;

            margin-bottom:18px;

            color:var(--color-text-secondary);
        }

        .footer-contact i{
            width:18px;

            color:var(--color-primary);
        }

        /* ========================================
           BOTTOM
        ======================================== */

        .footer-bottom{
            margin-top:70px;
            padding-top:30px;

            border-top:
                1px solid rgba(71,194,192,.12);

            display:flex;
            align-items:center;
            justify-content:space-between;

            gap:20px;

            color:var(--color-text-secondary);
        }

        /* ========================================
           SOCIALS
        ======================================== */

        .footer-socials{
            display:flex;
            align-items:center;
            gap:14px;
        }

        .footer-socials a{
            width:44px;
            height:44px;

            border-radius:14px;

            background:#fff;

            display:flex;
            align-items:center;
            justify-content:center;

            text-decoration:none;

            color:var(--color-primary);

            transition:.25s ease;

            box-shadow:
                0 8px 20px rgba(15,23,42,.05);
        }

        .footer-socials a:hover{
            transform:translateY(-3px);

            background:var(--color-primary);

            color:#fff;
        }

        /* ========================================
           MOBILE
        ======================================== */

        @media(max-width:991px){

            .site-footer{
                padding:70px 0 30px;
            }

            .footer-bottom{
                flex-direction:column;
                text-align:center;
            }

            .footer-brand{
                margin-bottom:20px;
            }
        }
    </style>
</head>
<body>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-4">
                    Новые люди • Гражданская платформа
                </div>

                <h1 class="hero-title">
                    Решайте городские проблемы быстро и удобно
                </h1>

                <p class="hero-subtitle">
                    Современное PWA-приложение для обращений граждан.
                    Сообщайте о проблемах, следите за статусом заявок
                    и взаимодействуйте с городскими службами онлайн.
                </p>

                <ul class="feature-list list-unstyled">
                    <li>
                        <i class="fa-solid fa-check"></i>
                        Жалобы и обращения в несколько кликов
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Работает как мобильное приложение (PWA)
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Поддержка фото, геолокации и уведомлений
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Контроль статуса решения проблемы
                    </li>
                </ul>

                <div class="d-flex gap-3 flex-wrap mt-5">
                    <a href="#"
                       class="btn btn-primary download-btn">
                        <i class="fa-solid fa-mobile-screen-button me-2"></i>
                        Открыть приложение
                    </a>

                    <a href="#problems"
                       class="btn btn-outline-dark download-btn">
                        <i class="fa-solid fa-list-check me-2"></i>
                        Категории проблем
                    </a>
                </div>

            </div>

            <div class="col-lg-6">

                <div class="phone-wrapper">

                    <div class="phone-frame">
                        <div class="phone-screen">

                            <!-- ВСТРОЕННОЕ PWA -->
                            <iframe src="${{env("APP_URL")}}/app"></iframe>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- ПРОБЛЕМЫ -->
<section class="section bg-white" id="problems">
    <div class="container">

        <h2 class="section-title">
            Какие проблемы можно решить?
        </h2>

        <p class="section-subtitle">
            Платформа объединяет десятки направлений для взаимодействия
            граждан с городскими службами и органами власти.
        </p>

        <div class="row g-4">

            <!-- Благоустройство -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-city"></i>
                    </div>

                    <h4>Благоустройство</h4>

                    <p>
                        Проблемы дорог, освещения,
                        уборки территории и городской среды.
                    </p>

                    <div class="problem-tags">
                        <span>Дороги</span>
                        <span>Освещение</span>
                        <span>Озеленение</span>
                    </div>

                </div>
            </div>

            <!-- ЖКХ -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>

                    <h4>ЖКХ и коммунальные услуги</h4>

                    <p>
                        Вода, отопление, электричество,
                        тарифы и работа управляющих компаний.
                    </p>

                    <div class="problem-tags">
                        <span>Отопление</span>
                        <span>Лифты</span>
                        <span>Электричество</span>
                    </div>

                </div>
            </div>

            <!-- Транспорт -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-car-side"></i>
                    </div>

                    <h4>Транспорт и безопасность</h4>

                    <p>
                        Общественный транспорт,
                        парковки и дорожная безопасность.
                    </p>

                    <div class="problem-tags">
                        <span>Парковки</span>
                        <span>ПДД</span>
                        <span>Переходы</span>
                    </div>

                </div>
            </div>

            <!-- Экология -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-leaf"></i>
                    </div>

                    <h4>Экология</h4>

                    <p>
                        Свалки, загрязнение,
                        незаконные вырубки и защита природы.
                    </p>

                    <div class="problem-tags">
                        <span>Свалки</span>
                        <span>Воздух</span>
                        <span>Животные</span>
                    </div>

                </div>
            </div>

            <!-- Здравоохранение -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>

                    <h4>Здравоохранение</h4>

                    <p>
                        Доступность медицинской помощи,
                        очереди и качество обслуживания.
                    </p>

                    <div class="problem-tags">
                        <span>Очереди</span>
                        <span>Лекарства</span>
                        <span>Поликлиники</span>
                    </div>

                </div>
            </div>

            <!-- Власть -->
            <div class="col-lg-4 col-md-6">
                <div class="problem-card">

                    <div class="problem-icon">
                        <i class="fa-solid fa-landmark"></i>
                    </div>

                    <h4>Взаимодействие с властью</h4>

                    <p>
                        Обращения в органы власти,
                        контроль ответов и госуслуг.
                    </p>

                    <div class="problem-tags">
                        <span>Госуслуги</span>
                        <span>МФЦ</span>
                        <span>Обращения</span>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- СТАТИСТИКА -->
<section class="section">
    <div class="container">

        <div class="stats-box">

            <div class="row text-center g-5">

                <div class="col-md-3">
                    <div class="stat-number">12+</div>
                    <div>Категорий проблем</div>
                </div>

                <div class="col-md-3">
                    <div class="stat-number">24/7</div>
                    <div>Доступность платформы</div>
                </div>

                <div class="col-md-3">
                    <div class="stat-number">PWA</div>
                    <div>Работает как приложение</div>
                </div>

                <div class="col-md-3">
                    <div class="stat-number">100%</div>
                    <div>Онлайн взаимодействие</div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">

    <div class="container">

        <div class="row g-5">

            <!-- BRAND -->
            <div class="col-lg-4">

                <div class="footer-brand">

                    <div class="footer-logo">
                        <i class="fa-solid fa-people-group"></i>
                    </div>

                    <div>
                        <h4>
                            Гражданская Платформа
                        </h4>

                        <p>
                            Современная PWA-платформа для взаимодействия
                            граждан с городскими службами и органами власти.
                        </p>
                    </div>

                </div>

            </div>

            <!-- NAVIGATION -->
            <div class="col-lg-2 col-6">

                <h5 class="footer-title">
                    Навигация
                </h5>

                <ul class="footer-links">
                    <li><a href="#">Главная</a></li>
                    <li><a href="#">Проблемы</a></li>
                    <li><a href="#">Обращения</a></li>
                    <li><a href="#">Статистика</a></li>
                </ul>

            </div>

            <!-- CATEGORIES -->
            <div class="col-lg-3 col-6">

                <h5 class="footer-title">
                    Категории
                </h5>

                <ul class="footer-links">
                    <li><a href="#">ЖКХ</a></li>
                    <li><a href="#">Транспорт</a></li>
                    <li><a href="#">Экология</a></li>
                    <li><a href="#">Благоустройство</a></li>
                </ul>

            </div>

            <!-- CONTACT -->
            <div class="col-lg-3">

                <h5 class="footer-title">
                    Контакты
                </h5>

                <ul class="footer-contact">
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        support@example.ru
                    </li>

                    <li>
                        <i class="fa-solid fa-phone"></i>
                        +7 (999) 123-45-67
                    </li>

                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        Российская Федерация
                    </li>
                </ul>

            </div>

        </div>

        <!-- BOTTOM -->
        <div class="footer-bottom">

            <p class="mb-0">
                © 2026 Гражданская Платформа. Все права защищены.
            </p>

            <div class="footer-socials">

                <a href="#">
                    <i class="fa-brands fa-vk"></i>
                </a>

                <a href="#">
                    <i class="fa-brands fa-telegram"></i>
                </a>

                <a href="#">
                    <i class="fa-brands fa-github"></i>
                </a>

            </div>

        </div>

    </div>

</footer>

</body>
</html>
