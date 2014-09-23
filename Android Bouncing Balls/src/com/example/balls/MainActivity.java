package com.example.balls;

import java.util.ArrayList;

import android.os.Bundle;
import android.app.Activity;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class MainActivity extends Activity {

	private Button reset;
	private Button plus;
	private Button minus;
	private TextView text;
	
	private float deltaInitVel;
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		
		reset = (Button)findViewById(R.id.reset);
		plus = (Button)findViewById(R.id.speed_plus);
		minus = (Button)findViewById(R.id.speed_minus);
		text = (TextView)findViewById(R.id.speed_text);
		text.setText(DrawView.getInitVel()+"");
		
		deltaInitVel = 0.05f;
		
		reset.setOnClickListener(new View.OnClickListener() {
			@Override
            public void onClick(View v) {
				DrawView.resetBalls();
			}
		});
		
		plus.setOnClickListener(new View.OnClickListener() {
			@Override
            public void onClick(View v) {
				DrawView.setInitVel(DrawView.getInitVel() + deltaInitVel);
				text.setText(DrawView.getInitVel()+"");
			}
		});
		
		minus.setOnClickListener(new View.OnClickListener() {
			@Override
            public void onClick(View v) {
				DrawView.setInitVel(DrawView.getInitVel() - deltaInitVel);
				text.setText(DrawView.getInitVel()+"");
			}
		});
	}
	


	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
