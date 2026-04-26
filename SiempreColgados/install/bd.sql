# Desde la raíz del proyecto Laravel:
mysqldump -u root -p siemprecolgados ^
    --result-file=../install/bd.sql ^
    --add-drop-table ^
    --skip-add-locks ^
    --single-transaction

# Si usas XAMPP con contraseña vacía, pulsa Enter cuando pida password