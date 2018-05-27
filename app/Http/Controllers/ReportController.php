<?php

namespace App\ Http\ Controllers;

use Illuminate\ Http\ Request;
use App\ User;
use App\ Bank;
use App\ Customer;
use App\ CustomerUser;
use App\ Http\ Assets\ ResourceFunctions;
use Illuminate\ Support\ MessageBag;
use Log;
use Excel;
use DB;
use Auth;
use App\ Order;
use Carbon\ Carbon;

class ReportController extends Controller {
    public
    function customer() { //dd(request()->from,request()->to);
        $customers = Customer::with('Customers_users.orders') -> get();

        $result = [];
        $to = date_format(Carbon::parse(request()->to)-> addDay(), 'Y-m-d');
        foreach($customers as $customer) {
            $amount = 0;
            $transactions = 0;
            foreach($customer -> Customers_users as $user) {
                $new_orders = !request() -> from ? $user -> orders : $user -> orders -> where('created_at', '>=', request() -> from) -> where('created_at', '<=', $to);
                foreach($new_orders as $order) {
                    $amount += $order -> amount;
                    $transactions++;
                }
            }
            array_push($result, [
                'customer' => $customer -> name,
                'transactions' => $transactions,
                'amount' => $amount,
            ]);
        }

        return $result;
    }

    public
    function index() {
        return view('report.index');
    }

    public
    function list() {

        $customer_search = request() -> customer_search;

        $customer_id = CustomerUser::where('customer_id', Auth::id()) -> pluck('customer_id');
        //todas las empresas para cargas el combo de carga masiva
        $customers = Customer::all();
        // busca el id, de las empresas donde id_admin sea igual al usuario logueado
        $customer_user = CustomerUser::whereIn('customer_id', $customer_id) -> pluck('user_id'); // devuelve todos los usuarios que pertenecen a esas empresas del usuario loqueado

        $users = User::with('customerUser') // devuelve los datos de la relación  with customer_user
            -> with('banks') // devuelve los datos de la relación  with banks
            -> search('', $customer_search) // llamado al scope employeesearch dentro modelo user
            -> where('deleted', false) // deleted false estoy perdido, donde esta lo que hicimos?         
            // ->whereIn('id', $customer_user) // comparando id table user con todo los id de las tabla customer user
            -> orderBy('id', 'desc') -> paginate();

        if (Auth::user() -> role == 'empresa') {
            $type = 'empresa';
        }

        $banks = Bank::all();

        return [
            'users' => $users,
            'banks' => $banks,
            'customers' => $customers,
            'type' => $type
        ];
    }


 public function order()
    {

      $orders= Order::with(['customer_users','customer_users.user'])->get();
      $result=[];
      foreach($orders as $order)
       array_push($result, [
          'users' =>  $users,
          'orders' =>  $orders,
          'customers' =>  $customers,
        ]);      
    } return $result;
}


