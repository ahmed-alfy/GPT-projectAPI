$json = file_get_contents(base_path('assets/json/file.json'));

         $data = json_decode($json, true);
        if(!empty( $json)){
         $dataa = array_merge($data, $newData);
        }else{
            $dataa = $newData ;
        }
         $json = json_encode($dataa,JSON_PRETTY_PRINT);
        // File::put(base_path('assets/json/file.json'), $json);
        file_put_contents(base_path('assets/json/file.json'), $json);

        $json = json_decode($json,true);
        foreach ($json as $record) {
            // Access each record's properties
            // $token = $record["_token"];
            $name = $record["name"];
            // $mobile = $record["mobile"];
            // $email = $record["email"];
            // $category_id = $record["category_id"];
            // $active = $record["active"];
            // $address = $record["address"];
            // $logo = $record["logo"];

            if($name == 'ali' )
            return $name;
            // Do something with the record's properties
            // Example: Display the record's name
            // echo $name . "\" \" ";
        }
        return $json;
