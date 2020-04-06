<!--TOGGLE MOBILE-->

<div class="menu-wrap">
    <input type="checkbox" class="toggler">
    <div class="hamburger">
        <div></div>
    </div>
    <div class="menu">
        <div>
            <div>
               <ul>

                <li>
                    <div class="style_axel"><a href="<?php echo $actuel; ?>?style=axel/index4.css"></a>
                        <div>
                </li>
                <li>
                    <div class="style_pol"><a href="<?php echo $actuel; ?>?style=pol/index2.css"></a></div>
                </li>
                <li>
                    <div class="style_steven"><a href="<?php echo $actuel; ?>?style=steven/index3.css"></a></div>
                </li>
                <li>
                    <div class="style_ilayda"><a href="<?php echo $actuel; ?>?style=index.css"></a></div>
                </li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="catalogue.php">Films</a></li>
                <?php if(isset($_SESSION["Pseudo"])): ?>
                        <li><span>Bonjour <?php echo $_SESSION["Pseudo"];?></span>
                        <br>
                        <a href="logout.php">déconnexion</a>
                        <a href="user_compte.php?ID=<?=$_SESSION["ID"]?>">compte</a>
                        </li>
                        <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
                        <li><a href="admin.php">Admin</a></li>
                        <?php endif?>
                <?php else:?>
                        <li><a href="inscription.php">S'inscrire</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                <?php endif?>
            </ul>
            </div>
        </div>
    </div>

</div>


<!--TITRE-->

<div class="title-dada">
    <h1> <a href="index.php"> ALLO SIMPLON</a></h1>
</div>


<!--NAV BAR-->

<div class="nav-dada">
    <div class="logo-dada">
        <h1><a class="lien-home" href="index.php">ALLO SIMPLON</a> </h1>
    </div>
    <div class="menu-nav">
        <form class="search-bar" action="search-result.php" method="GET">
            <input type="search" placeholder="Rechercher un film" name="search">
            <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
        </form>
        <div class="menu-dada">
            <ul>

                <li>
                    <div class="style_axel"><a href="<?php echo $actuel; ?>?style=axel/index4.css"></a>
                        <div>
                </li>
                <li>
                    <div class="style_pol"><a href="<?php echo $actuel; ?>?style=pol/index2.css"></a></div>
                </li>
                <li>
                    <div class="style_steven"><a href="<?php echo $actuel; ?>?style=steven/index3.css"></a></div>
                </li>
                <li>
                    <div class="style_ilayda"><a href="<?php echo $actuel; ?>?style=index.css"></a></div>
                </li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="catalogue.php">Films</a></li>
                <?php if(isset($_SESSION["Pseudo"])): ?>
                        <li><span>Bonjour <?php echo $_SESSION["Pseudo"];?></span>
                        <br>
                        <a href="logout.php">déconnexion</a>
                        <a href="user_compte.php?ID=<?=$_SESSION["ID"]?>">compte</a>
                        </li>
                        <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]):?>
                        <li><a href="admin.php">Admin</a></li>
                        <?php endif?>
                <?php else:?>
                        <li><a href="inscription.php">S'inscrire </a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                <?php endif?>
            </ul>
        </div>
    </div>
</div>

<div class="vide"></div>