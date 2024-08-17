function eliminarRegistro(idEmpleado) {
            if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "eliminar_empleado.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        // Eliminación exitosa, actualizar la tabla y ajustarla
                        location.reload();
                    }
                };
                xhr.send("id_empleado=" + encodeURIComponent(idEmpleado));

            }
        }

        $(document).ready(function () {
            adjustTable();
        });

        $(window).resize(function() {
            if($(window).width() === $(window).outerWidth()) {
                adjustTable();
            }
        });

        function adjustTable() {
            var table = $("table");
            table.css("table-layout", "auto");
            table.width("100%");
        }