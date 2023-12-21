<?php

$route_data = [
    ['from' => 'A', 'to' => 'B', 'price' => 15, 'time' => 20, 'oneWay' => false],
    ['from' => 'A', 'to' => 'C', 'price' => 45, 'time' => 60, 'oneWay' => false],
    ['from' => 'B', 'to' => 'C', 'price' => 25, 'time' => 40, 'oneWay' => false],
    ['from' => 'B', 'to' => 'F', 'price' => 30, 'time' => 30, 'oneWay' => false],
    ['from' => 'C', 'to' => 'D', 'price' => 60, 'time' => 60, 'oneWay' => false],
    ['from' => 'E', 'to' => 'F', 'price' => 40, 'time' => 20, 'oneWay' => false],
    ['from' => 'D', 'to' => 'F', 'price' => 80, 'time' => 50, 'oneWay' => false],
    ['from' => 'C', 'to' => 'G', 'price' => 60, 'time' => 60, 'oneWay' => false],
    ['from' => 'D', 'to' => 'G', 'price' => 10, 'time' => 50, 'oneWay' => true],
    ['from' => 'F', 'to' => 'G', 'price' => 80, 'time' => 30, 'oneWay' => false]
];

function getRoute(string $from, string $to, array $route_data) : array
{
    if (!$from || !$to || empty($route_data)) return [];

    $nodes = [];

    foreach ($route_data as $key => $route) {
        $route_data[$key]['total_cost'] = $route['price'] + $route['time'];

        if (!array_key_exists($route['from'], $nodes)) {
            $nodes[$route['from']] = [
                'near_nodes' => [
                    $route['to'] => $route_data[$key]['total_cost']
                ]
            ];
        } elseif (!in_array($route['to'], $nodes[$route['from']]['near_nodes'])) {
            $nodes[$route['from']]['near_nodes'][$route['to']] = $route_data[$key]['total_cost'];
        }

        if (!array_key_exists($route['to'], $nodes)) {
            $nodes[$route['to']] = [
                'near_nodes' => [
                    $route['from'] => $route_data[$key]['total_cost']
                ]
            ];
        } elseif (!in_array($route['from'], $nodes[$route['to']]['near_nodes'])) {
            if (!$route['oneWay']) {
                $nodes[$route['to']]['near_nodes'][$route['from']] = $route_data[$key]['total_cost'];
            }
        }
    }

    $all_possible_routes = [];

    $current_node_title = $from;
    $current_node = $nodes[$from];

    $possible_routes = [
        [
            'nodes'  => [$current_node_title],
            'length' => 0
        ]
    ];

    $path_length = 0;

    while (true) {
        foreach ($possible_routes as $route) {
            $node_title = $route['nodes'][array_key_last($route['nodes'])];

            if ($node_title == $to) continue;

            $new_routes = [];

            foreach ($nodes[$node_title]['near_nodes'] as $near_node_title => $near_node_cost) {
                if (in_array($near_node_title, $route['nodes'])) {
                    continue;
                }

                $route_nodes = $route['nodes'];
                $route_nodes[] = $near_node_title;

                $new_routes[] = [
                    'nodes'  => $route_nodes,
                    'length' => $route['length'] + $near_node_cost,
                ];
            }
        }

        if (!$new_routes) {
            break;
        }

        $possible_routes = $new_routes;
        $all_possible_routes = array_merge($all_possible_routes, $possible_routes);

        $path_length++;

        if ($path_length > count($nodes)) {
            break;
        }
    }

    foreach ($all_possible_routes as $key => $route) {
        if ($route['nodes'][array_key_last($route['nodes'])] != $to) {
            unset($all_possible_routes[$key]);
        }
    }

    usort($all_possible_routes, function ($a, $b) {
        return $a['length'] - $b['length'];
    });

    return $all_possible_routes[0]['nodes'];
}

echo implode(',', getRoute('A', 'G', $route_data));