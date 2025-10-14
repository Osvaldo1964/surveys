<?php
echo 'salir';
session_destroy();

echo '<script>
    localStorage.removeItem("user");
    localStorage.removeItem("username");
    localStorage.removeItem("token_user");
    localStorage.removeItem("rol_user");
    localStorage.removeItem("num_contract");
    localStorage.removeItem("class_user");
    window.location = "/";
</script>';
