package com.example.omnibus_ph;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.content.IntentFilter;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.os.Handler;

import com.example.omnibus_ph.utility.NetworkChangeListener;

public class HomeActivity extends AppCompatActivity {
//    NetworkChangeListener networkChangeListener = new NetworkChangeListener();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
//                Intent homeIntent = new Intent(MainActivity.this,HomeActivity.class);
//                startActivity(homeIntent);
                startActivity(new Intent(HomeActivity.this, MainActivity.class));
                finish();
            }
        }, 2000);
    }
//    *******WIP******
//    adding Internet checker/ handler
//
//    @Override
//    protected void onStart() {
//        IntentFilter filter = new IntentFilter(ConnectivityManager.CONNECTIVITY_ACTION);
//        registerReceiver(networkChangeListener, filter);
//        super.onStart();
//    }
//
//    @Override
//    protected void onStop() {
//        unregisterReceiver(networkChangeListener);
//        super.onStop();
//    }
}