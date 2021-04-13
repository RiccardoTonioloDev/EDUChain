<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/personalDashboardSaldo.css">
    <title>Login & Register</title>
</head>
<body>
<div class="navbar">
        <div class="sideLeft">
            <img class="logo" src="../../Images/EduchainLogo-BlackV.png" alt="Logo">
            <div class="nav-text">EDUChain</div>
        </div>
        <div class="sideRight">
            <ul class="nav-links">
            <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/dog-house-2white.png" alt="Home">
                        <div>Saldo</div>
                    </a>     
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/login-3white.png" alt="Accedi">
                        <div>Invia</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/login-3white.png" alt="Accedi">
                        <div>Ricevi</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/login-3white.png" alt="Accedi">
                        <div>Genera</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/login-3white.png" alt="Accedi">
                        <div>Ritira</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../../Images/login-3white.png" alt="Accedi">
                        <div>Blockchain</div>
                    </a>    
                </li>
            </ul>
            <div class="hamburger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
    </div>
    <script>
        const navSlide = () =>{
            const burger = document.querySelector('.hamburger');
            const nav = document.querySelector('.nav-links');
            const navLinks = document.querySelectorAll('.nav-button');


            burger.addEventListener('click',()=>{
                nav.classList.toggle('nav-active');
                    
                navLinks.forEach((link,index) =>{
                    if(link.style.animation){
                        link.style.animation = "";
                    }else{
                        link.style.animation = "navLinkFade 0.5s ease forwards "+(index / 7)+"s";
                    }
                });
                burger.classList.toggle("toggle");
            });
                
        }
            navSlide();
    </script>
</body>
</html>