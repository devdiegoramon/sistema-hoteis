<?php

session_start();
session_destroy();
header("Location: ../../index.php?text=Logout realizado com sucesso");
