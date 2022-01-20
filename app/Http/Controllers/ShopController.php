<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookOrder;
use App\City;
use App\Http\Resources\Provinces as ProvinceResourceCollection;
use App\Http\Resources\Cities as CityResourceCollection;
use App\Order;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function provinces()
    {
        return new ProvinceResourceCollection(Province::get());
    }

    public function cities()
    {
        return new CityResourceCollection(City::get());
    }

    public function shipping(Request $request)
    {
        $user = Auth::user(); // mendapatkan current user yang login
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        if ($user) {
            $this->validate($request, [
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
            ]);
            $user->name = $request->name;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->province_id = $request->province_id;
            $user->city_id = $request->city_id;
            if ($user->save()) {
                $status = "success";
                $message = "Update shipping success";
                $data = $user->toArray();
            } else {
                $message = "Update shipping failed";
            }
        } else {
            $message = "User not found";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function couriers()
    {
        $couriers = [
            ['id' => 'jne', 'text' => 'JNE'],
            ['id' => 'tiki', 'text' => 'TIKI'],
            ['id' => 'pos', 'text' => 'POS'],
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'couriers data',
            'data' => $couriers
        ], 200);
    }

    // Validasi kelengkapan data
    // 1. data belanja
    // 2. data courier
    // 3. data kota pengiriman dari tabel user

    // Validasi data belanja
    // 1. Cek stok barang
    // 2. Update data belanja sesuai stok
    // Request data services dari API RajaOngkir

    // Response
    // 1. Daftar services jika ada
    // 2. Data belanja yang telah diupdate
    // 3. Informasi jumlah belanja vs stok

    public function services(Request $request)
    {
        $status = "error";
        $message = "";
        $data = [];

        // Validasi kelengkapan data
        $this->validate($request, [
            'courier' => 'required',
            'carts' => 'required',
        ]);

        $user = Auth::user();

        if ($user) {
            $destination = $user->city_id;

            if ($destination > 0) {
                // hardcode, silakan sesuaikan dengan asal pengiriman barangnya
                $origin = 153; // Jakarta Selatan
                $courier = $request->courier;
                $carts = $request->carts;
                $carts = json_decode($carts, true); // transformasi dari json menjadi array

                // validasi data belanja
                $validCart = $this->validateCart($carts);
                $data['safe_carts'] = $validCart['safe_carts'];
                $data['total'] = $validCart['total'];
                $quantity_different = $data['total']['quantity_before'] <> $data['total']['quantity'];
                $weight = $validCart['total']['weight'] * 1000;

                if ($weight > 0) {
                    // request courier service API RajaOngkir
                    $parameter = [
                        "origin" => $origin,
                        "destination" => $destination,
                        "weight" => $weight,
                        "courier" => $courier
                    ];

                    // check ongkos kirim ke api RajaOngkir melalui fungsi getServices()
                    $respon_services = $this->getServices($parameter);
                    if ($respon_services['error'] == null) {
                        $services = [];
                        $response = json_decode($respon_services['response']); // transformasi dari json menjadi array
                        $costs = $response->rajaongkir->results[0]->costs;

                        foreach ($costs as $cost) { // parsing ongkos kirimnya
                            $service_name = $cost->service;
                            $service_cost = $cost->cost[0]->value;
                            $service_estimation = str_replace('hari', '', trim($cost->cost[0]->etd));

                            $services[] = [
                                'service' => $service_name,
                                'cost' => $service_cost,
                                'estimation' => $service_estimation,
                                'resume' => $service_name . ' [ Rp.
                                ' . number_format($service_cost) . ', Etd: ' . $cost->cost[0]->etd . ' day(s) ]'
                            ];
                        }

                        // Response
                        if (count($services) > 0) {
                            $data['services'] = $services;
                            $status = "success";
                            $message = "getting services success";
                        } else {
                            $message = "courier services unavailable";
                        }

                        // ketika ternyata jumlah beli berbeda dengan jumlah stok maka tampilkan warninng
                        if ($quantity_different) {
                            $status = "warning";
                            $message = "Check cart data, " . $message;
                        }
                    } else {
                        $message = "cURL Error #:" . $respon_services['error'];
                    }
                } else {
                    $message = "weight invalid";
                }
            } else {
                $message = "destination not set";
            }
        } else {
            $message = "user not found";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    protected function validateCart($carts)
    {
        $safe_carts = [];

        $total = [
            'quantity_before' => 0,
            'quantity' => 0,
            'price' => 0,
            'weight' => 0,
        ];
        $idx = 0;

        foreach ($carts as $cart) {
            $id = (int) $cart['id'];
            $quantity = (int) $cart['quantity'];
            $total['quantity_before'] += $quantity;
            $book = Book::find($id); // ambil data buku berdasarkan id-nya

            if ($book) {
                if ($book->stock > 0) {
                    $safe_carts[$idx]['id'] = $book->id;
                    $safe_carts[$idx]['title'] = $book->title;
                    $safe_carts[$idx]['cover'] = $book->cover;
                    $safe_carts[$idx]['price'] = $book->price;
                    $safe_carts[$idx]['weight'] = $book->weight;

                    // jika jumlah pesan melebihi dari stok
                    if ($book->stock < $quantity) {
                        $quantity = (int) $book->stock;
                    }

                    $safe_carts[$idx]['quantity'] = $quantity;
                    $total['quantity'] += $quantity; // total jumlah yang dipesan di hitung kembali
                    $total['price'] += $book->price * $quantity; //total price dihitung kembali
                    $total['weight'] += $book->weight * $quantity; // total berat dihitung kembali
                    $idx++;
                } else {
                    continue;
                }
            }
        }

        return [
            'safe_carts' => $safe_carts,
            'total' => $total
        ];
    }

    protected function getServices($data)
    {
        $url_cost = "https://api.rajaongkir.com/starter/cost";
        $key = "31b7aa3fc581f43563a58e6d44ae2fd6";
        $postdata = http_build_query($data);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url_cost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
                "key: " . $key
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return [
            'error' => $error,
            'response' => $response,
        ];
    }

    // validasi kelengkapan data
    // buat data order
    // buat data detail order
    // check ongkir
    // update data order
    // respon

    public function payment(Request $request)
    {
        $error = 0;
        $status = "error";
        $message = "";
        $data = [];
        $user = Auth::user();

        if ($user) {
            $this->validate($request, [
                'courier' => 'required',
                'service' => 'required',
                'carts' => 'required',
            ]);

            DB::beginTransaction();

            try {
                // prepare data
                $origin = 153; // Jakarta Selatan
                $destination = $user->city_id;
                if ($destination <= 0) $error++;
                $courier = $request->courier;
                $service = $request->service;
                $carts = json_decode($request->carts, true);

                $order = new Order();
                $order->user_id = $user->id;
                $order->total_price = 0;
                $order->invoice_number = date('YmdHis');
                $order->courier_service = $courier . '-' . $service;
                $order->status = 'SUBMIT';

                if ($order->save()) {
                    $total_price = 0;
                    $total_weight = 0;

                    foreach ($carts as $cart) {
                        $id = (int) $cart['id'];
                        $quantity = (int) $cart['quantity'];
                        $book = Book::find($id);
                        if ($book) {
                            if ($book->stock >= $quantity) {
                                $total_price += $book->price * $quantity;
                                $total_weight += $book->weight * $quantity;
                                // create book order
                                $book_order = new BookOrder;
                                $book_order->book_id = $book->id;
                                $book_order->order_id = $order->id;
                                $book_order->quantity = $quantity;
                                if ($book_order->save()) {
                                    // kurangi stock
                                    $book->stock = $book->stock - $quantity;
                                    $book->save();
                                }
                            } else {
                                $error++;
                                throw new \Exception('Out of stock');
                            }
                        } else {
                            $error++;
                            throw new \Exception('Book is not found');
                        }
                    }

                    $total_bill = 0;
                    $weight = $total_weight * 1000; // to gram
                    if ($weight <= 0) {
                        $error++;
                        throw new \Exception('Weight null');
                    }

                    $data = [
                        "origin" => $origin,
                        "destination" => $destination,
                        "weight" => $weight,
                        "courier" => $courier
                    ];
                    $data_cost = $this->getServices($data);
                    if ($data_cost['error']) {
                        $error++;
                        throw new \Exception('Courier service unavailable');
                    }

                    $response = json_decode($data_cost['response']);
                    $costs = $response->rajaongkir->results[0]->costs;
                    $service_cost = 0;
                    foreach ($costs as $cost) {
                        $service_name = $cost->service;
                        if ($service == $service_name) {
                            $service_cost = $cost->cost[0]->value;
                            break;
                        }
                    }
                    if ($service_cost <= 0) {
                        $error++;
                        throw new \Exception('Service cost invalid');
                    }

                    $total_bill = $total_price + $service_cost;
                    // update total bill order
                    $order->total_price = $total_bill;

                    if ($order->save()) {
                        if ($error == 0) {
                            DB::commit();
                            $status = 'success';
                            $message = 'Transaction success';

                            /* MULAI MIDTRANS */
                            \Veritrans_Config::$serverKey = 'SB-Mid-server-VI9190k6GY2UxY-_u5zjhoA8';
                            \Veritrans_Config::$isProduction = false;
                            \Veritrans_Config::$isSanitized = true;
                            \Veritrans_Config::$is3ds = true;

                            $transaction_data = [
                                'transaction_details' => [
                                    'order_id' => $order->invoice_number,
                                    'gross_amount' => $total_bill,
                                ]
                            ];


                            $payment_link = \Veritrans_Snap::createTransaction($transaction_data)->redirect_url;

                            $data = [
                                'payment_link' => $payment_link,
                            ];

                            // $data = [
                            //     'order_id' => $order->id,
                            //     'total_price' => $total_bill,
                            //     'invoice_number' => $order->invoice_number,
                            // ];
                        } else {
                            $message = 'There are ' . $error . ' errors';
                        }
                    }
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
                DB::rollback();
            }
        } else {
            $message = 'User Not Found';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function myOrder(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = [];

        if ($user) {
            $orders = \App\Order::select('*')
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'DESC')
                ->get();

            $status = "success";
            $message = "data my order ";
            $data = $orders;
        } else {
            $message = 'User Not Found';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], 200);
    }
}
