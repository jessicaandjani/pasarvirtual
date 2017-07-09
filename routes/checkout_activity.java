package com.example.appsname;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkError;
import com.android.volley.NetworkResponse;
import com.android.volley.NoConnectionError;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.ServerError;
import com.android.volley.TimeoutError;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.HttpHeaderParser;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Objects;

/**
 * Created by asus on 4/7/2017.
 */
public class CheckOutActivity extends AppCompatActivity {
    private Toolbar toolbar;
    private EditText inputName, inputAddress, inputPhone;
    private TextView subtotal, shipping, orderDetails, orderList;
    private Button confirmBtn, backBtn;
    private LinearLayout buyerForm, fixedOrderList, details;
    private String name, address, phone, totalProduct;
    private int mExpandedPosition = -1;
    private String[] orderName = {
            "Wortel", "Kacang Panjang", "Tomat", "Buncis", "Kangkung", "Bayam"
    };
    private int[] orderImage = {
            R.drawable.wortel, R.drawable.kacang_panjang, R.drawable.tomat,
            R.drawable.buncis, R.drawable.kangkung, R.drawable.bayam
    };
    private LayoutInflater mInflater;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_check_out);

        //Toolbar Section
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        toolbar.setNavigationIcon(R.drawable.ic_arrow_back);
        getSupportActionBar().setTitle("Konfirmasi");
        toolbar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onBackPressed();
            }
        });
        //Gallery Order List
        mInflater = LayoutInflater.from(this);
        initView();
        //Form Section
        buyerForm = (LinearLayout) findViewById(R.id.buyer_form);
        buyerForm.setFocusableInTouchMode(true);
        buyerForm.requestFocus();
        inputName = (EditText) findViewById(R.id.input_name);
        inputName.setText("Graciel", TextView.BufferType.EDITABLE);
        inputAddress = (EditText) findViewById(R.id.input_address);
        inputAddress.setText("Jl Pajajaran No.12, Bandung", TextView.BufferType.EDITABLE);
        inputPhone = (EditText) findViewById(R.id.input_phone);
        inputPhone.setText("081223456789", TextView.BufferType.EDITABLE);
        //Cost Information
        details = (LinearLayout) findViewById(R.id.expand_details);
        orderList = (TextView) findViewById(R.id.order_list);
        orderDetails = (TextView) findViewById(R.id.order_details);
        orderDetails.setText("Hello");
        final boolean isExpanded = mExpandedPosition == 0;
        details.setVisibility(isExpanded ? View.VISIBLE : View.GONE);
        orderList.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mExpandedPosition = isExpanded ? -1 : 0;
            }
        });
        subtotal = (TextView) findViewById(R.id.subtotal);
        subtotal.setText(getSubTotal());
        shipping = (TextView) findViewById(R.id.delivery_cost);
        shipping.setText("Rp 5000/-");
        //Back Button
        backBtn = (Button) findViewById(R.id.back_button);
        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(CheckOutActivity.this, ShoppingListActivity.class);
                startActivity(intent);
            }
        });
        //Add Order to Database
        confirmBtn = (Button) findViewById(R.id.confirm_button);
        confirmBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                submitForm();
            }
        });
    }

    private void initView()
    {
        fixedOrderList = (LinearLayout) findViewById(R.id.fixed_order_list);
        for (int i = 0; i < orderName.length; i++) {
            View view = mInflater.inflate(R.layout.activity_fixed_order, fixedOrderList, false);
            ImageView img = (ImageView) view.findViewById(R.id.fixed_order_img);
            img.setImageResource(orderImage[i]);
            TextView txt = (TextView) view.findViewById(R.id.fixed_order_name);
            txt.setText(orderName[i]);
            fixedOrderList.addView(view);
        }
    }

    //Validating and Submit Form
    private void submitForm() {
        if (!validateName()) {
            return;
        }
        if (!validateAddress()) {
            return;
        }
        if (!validatePhone()) {
            return;
        }
        // add order and shopping list to database
        String ORDER_URL = "http://192.168.100.34:8000/order/add";
        addOrder(ORDER_URL);
    }

    private void addOrder(String URL) {
        name = inputName.getText().toString().trim();
        SharedPreferences sharedPreferences = getSharedPreferences("ShoppingList", Context.MODE_PRIVATE);
        ArrayList<ShoppingList> order = ShoppingListItems.retrieveShoppingList(sharedPreferences);
        totalProduct = String.valueOf(order.size());
        Log.d("total product", totalProduct);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("id order", response);
                        String ORDER_LINE_URL = "http://192.168.100.34:8000/order/add/" + response;
                        addOrderLine(ORDER_LINE_URL);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                    }
                }
        ) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("total_product", totalProduct);
                params.put("buyer_id", "1");
                params.put("orderstatus_id", "1");
                Log.d("params order", String.valueOf(params));
                return params;
            }
        };
        RequestQueue requestQueue = Volley.newRequestQueue(CheckOutActivity.this);
        requestQueue.add(stringRequest);
    }

    private void addOrderLine (String URL) {
        final SharedPreferences sharedPreferences = getSharedPreferences("ShoppingList", Context.MODE_PRIVATE);
        ArrayList<ShoppingList> order = ShoppingListItems.retrieveShoppingList(sharedPreferences);
        Gson gson = new Gson();
        final String data = gson.toJson(order); // change to json array

        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(CheckOutActivity.this,response,Toast.LENGTH_LONG).show();
                        // remove shopping list from SharedPreference
                        SharedPreferences.Editor editor = sharedPreferences.edit();
                        editor.clear();
                        editor.commit();
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        error.printStackTrace();
                    }
                }
        ) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("order_line", data);
                return params;
            }
        };
        RequestQueue requestQueue = Volley.newRequestQueue(CheckOutActivity.this);
        requestQueue.add(stringRequest);
    }

    public String getSubTotal() {
        SharedPreferences sharedPreferences = getSharedPreferences("ShoppingList", Context.MODE_PRIVATE);
        ArrayList<ShoppingList> order = ShoppingListItems.retrieveShoppingList(sharedPreferences);
        int subTotalMin = 0, subTotalMax = 0;
        for(int j = 0; j < order.size(); j++) {
            subTotalMin += Integer.parseInt(order.get(j).getPriceMin());
            subTotalMax += Integer.parseInt(order.get(j).getPriceMax());

        }
        String subTotal = "Rp " + subTotalMin + "-" + subTotalMax + "/-";
        return subTotal;
    }

    private boolean validateName() {
        if (inputName.getText().toString().trim().isEmpty()) {
            inputName.setError(getString(R.string.err_msg_name));
            requestFocus(inputName);
            return false;
        }
        return true;
    }

    private boolean validateAddress() {
        if (inputAddress.getText().toString().trim().isEmpty()) {
            inputAddress.setError(getString(R.string.err_msg_address));
            requestFocus(inputAddress);
            return false;
        }
        return true;
    }

    private boolean validatePhone() {
        if (inputPhone.getText().toString().trim().isEmpty()) {
            inputPhone.setError(getString(R.string.err_msg_phone));
            requestFocus(inputPhone);
            return false;
        }
        return true;
    }

    private void requestFocus(View view) {
        if (view.requestFocus()) {
            getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_VISIBLE);
        }
    }

    private class MyTextWatcher implements TextWatcher {

        private View view;

        private MyTextWatcher(View view) {
            this.view = view;
        }

        public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {
        }

        public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
        }

        public void afterTextChanged(Editable editable) {
            switch (view.getId()) {
                case R.id.input_name:
                    validateName();
                    break;
                case R.id.input_address:
                    validateAddress();
                    break;
                case R.id.input_phone:
                    validatePhone();
                    break;
            }
        }
    }
}
