<!-- Footer -->

<?php
	//session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Footer</title>
    <link rel="stylesheet" href="../Public/CSS/DiagHealth_footer.css">
    <link rel="stylesheet" href="../Public/CSS/DiagHealth_base.css">
</head>
<div class="espace"></div>
<footer>
    <div class="footer_logostart">
        <a href="DiagHealth_accueil.php"><img src="../Public/Images/logo.png" alt="" width="70px" /></img></a>
    </div>
    <div class="footer_social_icons">
        <ul>
            <li><a href="#" target="blanck"><img class="facebook"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAACYElEQVRIS8WWP3LTQBTGv7caSOlwAswM9qTDKcDucE6AHUEd34DhBCgnYHICTE2MxQkgFbYpcJmJPIO4gV06nuxjVlhBkldarRpvaevt733v7xL2dGhPXFiDn3Y+d8FODcytyGmiOQSWix+nVzYiSoEbz8ctCPkWQA/AYQ5gCcCHFBfBz/7c5EQhuN4aHz44kB8ADEwXZf4fbtbiXTjvK2e0Jxe8VTkGULeExp+HkKKfp14L3kK/FYS1rC9LSHGig++At+H9VUYpAX8Y5BPxfUhZogvCy4Rn4WYtjrNh3wE3Ol+GYD4zS6KLzZq87IXNzshjxvuUPdGnYHKaqpMUeBtipbb4MK6CmdvVfaQFA3BIPLme9MPYJg0uqZaY+zez134SHPU3OUySlbLdLsioToPbI5WrmkkwpDhOFkyzfekz6JXBbhlM3Uc7ipXHxEJVsvEEUzfrMBuNADDJk8Xkzfdo4MUGzReXPSZSfWs8VcHJFP0H66oxx4XKYML5zcT10oqLwAVVnPWtKN+kAzc6owEYH7UiLcCN9kgtiGe6e7ShLiwuO3B+oSW6oWw7hcwYxioWM/c8qUgNDSkRAYkQ5VBzVsHUvV+plQZIpeIqGiBHnXH9juVvUz9VAReOTAUsM4WswaYlocBqLT48kHMGHucptwGr1Xm7Fi3jWlSw7ZZSo007ty3AK0jRLfUQiFUqOAnp65SXAUePBCl6Vk+fGP4v7HfD7OYxgQn89XbtDCo99pL5jaod7IFZPW9rOeAViHwH5CUXfl6dlHpXJ41VCrLh0/1maklrsOnCsv/vDfwX2/FCLmrpZ4MAAAAASUVORK5CYII=" /></a>
            </li>
            <li><a href="#" target="blanck"><img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAACK0lEQVRIS+1WQXbSUBS9N6AdylypJC6guALpDnAFpSsoDqzMjLNQB9Yd0BWAK1B30C5Ag02dyxCP/Od5IbGBhORXzrEDmyH//Xvfu/++9yBu6eMt8eKO+J8pv7XUrX7Y+HUfR6T0ALQATEU4qv/E++mp+0MraQ1C/R3TwJ2mla0QK8hiR8a1OZ+nl8okePgybDuOjBPC9dA4AYfSFYDR0GtnA1aIm8dhF5QxgXNnzv0q8uarr+cA9sqSE+IbQFUDV4H7qbDi3cEXX4Sv9VDJF4aH39+6Cp770iQtTKFy0xh2sliFFWfBROjXHZxl30fPs0lWkM/WSZPCrq8lb6wGeJAHkwngjGrEhSZhS0zKm8vgib+Ol3P17nHYF8o7Cwk1wditZZ8VcXMQ9iBmD6A6sFMFanVOHkaBOyqt+NEg7FDkoxWgZZAxfFpk0JzUNi1iyalhs2joNYric8TJUNB+KzDYDSiXoR+iode1ItYgHXELiA/BwY2pMheE3M8OjSxW4axOhsPRVgYjPkeBt9GghcTl/WynwSZTpbc3bid9a9ZkQsFjO6rrKApfXJ64p6X9XQUa9zakY/3exFkUePFS+Gtildzs4EDUaEBhW6yAW5LqnT9Sxwv93nLFOURLaDoAtRWqCYEZyH7RhNpU9cobx5Nr2UbPqqRKzmcCGdXnjl+1u0tHZnqofWxgeiJsg2hkErkAoft1CsNJdOJOLBPMhW39n+uO2FaB/0/q318B4B/XZZO3AAAAAElFTkSuQmCC" /></a>
            </li>
            <li><a href="#" target="blanck"><img class="youtube"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAABUklEQVRIS+2W3VXCQBCFv/Ek5/imViAdSAdKB9KBHWgHYgWWoB2IFQgd0IHYAe+ujmeWIHGNOoZAeGDeNufeuZm/nRVaMmlJl53wxjK/XalWsjPgtBS+nZ2mU5DpHCwz0IkQxin5S8RK3oX3B5COU8UJs5/Z6wuvkwUhEc5GSaROxy7YSAi9b8LKfgfCs8tFbVA4EpjFIix8FHV9qu3TR+wJwbJaFs4vQO8c/EfAeuDEgU0g2hfehmnEA+Da4exGCAMlvwI1zoGDs4BE7krCRlY4hMwcXTrFmxFe9kdszHvHRKxF2Prjr4umGeEi1ZbmWDeHrS6sxCm4JdbZbVXC7nGycbBx6rrllsCqOY6LoY0LpKUrcz6Xa10SYyF8dn3FWmQIelyjfr9Q5AU4/3EtJgujPJPWSP/p3rgICrN1WD7Hz9v19Gk2zdXedhFvIsvtNtcHrDZ2HxZzvJMAAAAASUVORK5CYII=" /></a>
            </li>
            <li><a href="#" target="blanck"><img class="linkedin"
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAABbUlEQVRIS+2X4VGDQBCF3xMLiBXkrECsQK3AEoTkr4l0YOyASfybXKzEdKAdeHRAAcI6lxEGcEZDcpI4Iz+PY7/bt7tvDmJPD/fExeGAVaQVMuk7VcJjYuLQVGPWMrZQyeTNKfQzGD2eVuF18K2+FMrzr4CFV+YxXBWxNwEnAHaWnm3AhIRmNlyqSPckE3vas23VaANOktlAFSA1mgcC6i7AoMcTE4ephanxIhbBXSdgACsScZ7TJyUC0OsKvC3ny3dtagy7uYiQQwISNyJ4OgKX63XmvoABPQYmDl/XJYm0yjOJCVzXDKNNVyezQTluajyfiPCelAczHU4KCIC06IMqqD9a2Cm4KOfWJbjs+Ej3mvDmFLSS+qeMG92e0uN5YYuq4YLOwf3RQko5K2Wwa7V3LqVWkfYlk5fuwU05O8v4H/znpXZm1NZd24xTd+B9XfZK439HefNwkvUxzLfXWyeQDYMczp/EhgfeedsHfoWuLghKbj0AAAAASUVORK5CYII=" /></a>
            </li>
        </ul>
    </div>
    <div class="footer_menu_one">
        <ul>
            <li><a href="DiagHealth_accueil_plus.php"><i class="fas fa-info-circle"></i> A propos</a></li>
            <li><a href="DiagHealth_contact.php"><i class="fas fa-mail-bulk"></i> Contact</a></li>
            <li><a href="DiagHealth_CGU.php"><i class="far fa-copyright"></i> CGU-Mentions légales</a></li>
            <li><a href="DiagHealth_faq.php"><i class="far fa-question-circle"></i> FAQ</a></li>
            <li><a href="DiagHealth_forum.php"><i class="far fa-comment-dots"></i> Forum</a></li>
        </ul>
    </div>
    <div class="footer_bottom">
        <p>Design by: <a href="#">DiagHealth</a></p>
    </div>
</footer>