package com.example.memory;

import android.app.Activity;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;


//This activity MUST BE ADDED TO AndroidManifest.xml
public class SelectImageActivity extends FragmentActivity{
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.image_list);
		
		FragmentManager fm = getSupportFragmentManager(); 
		
		Fragment frag = fm.findFragmentById(R.id.imageContainer); 
		
		if (frag == null) {
			frag = new ImageList();
			FragmentTransaction fta = fm.beginTransaction(); 
			fta.add(R.id.imageContainer, frag);
			fta.commit(); 
		}
	}
}
