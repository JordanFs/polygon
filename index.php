<html>
<head>
</head>
<body>
<?php
    $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?


    function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

    function pointInPolygon($point, $polygon, $pointOnVertex = true) {

        // Transform string coordinates into arrays with x and y values
        $point = pointStringToCoordinates($point);
        $vertices = array();
        foreach ($polygon as $vertex) {
            $vertices[] = pointStringToCoordinates($vertex);
        }

        // Check if the point sits exactly on a vertex
        if ($pointOnVertex == true and pointOnVertex($point, $vertices) == true) {
            return "no vértice";
        }

        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1];
            $vertex2 = $vertices[$i];

            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "na borda";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "na borda";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }

        // If the number of edges we passed through is odd, then it's in the polygon.
        if ($intersections % 2 != 0) {
            return "dentro";
        } else {
            return "fora";
        }
    }

    function pointOnVertex($point, $vertices)
    {
        foreach ($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }


    // Obtenha o polígono do banco de dados e salve num array nesse formato, sabendo
    // que o último ponto deve ser igual ao primeiro pra fechar o polígono
    $poligono = array(
        '-15.500237, -48.200553',
        '-15.500501, -47.417421',
        '-16.050609, -47.415094',
        '-16.051630, -48.233314',
        '-15.500237, -48.200553'
    );

//        '-15.500237, -48.200553',
//        '-15.500501, -47.417421',
//        '-16.050609, -47.415094',
//        '-16.051630, -48.233314',
//        '-15.500237, -48.200553'





    // Coloque as coordenadas obtidas pelo $_GET assim
    $coordenada = "-15.880285, -47.863402";

    echo "o ponto ($coordenada): está ".
        pointInPolygon($coordenada, $poligono).
        " do polígono";

    // Saída: o ponto (-22.91602,-41.97678) está no vertice do polígono
?>
</body>
</html>