package com.example.memory;


import java.util.ArrayList;




import android.os.Bundle;
import android.support.v4.app.ListFragment;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TextView;



public class ImageList extends ListFragment
{
	private RecordAdapter adapter;
	private ArrayList<ImageRecord> catalog;
	
	
	@Override 
	public void onCreate(Bundle saved) {
		super.onCreate(saved); 
		
		catalog = Catalog.get(getActivity()).getRecords(); 
		
		adapter = new RecordAdapter(catalog); 
		setListAdapter(adapter);
	}
	
	
	//Refresh the list upon resume
	@Override
	public void onResume()
	{
		super.onResume();
		
		Catalog.refresh(getActivity());
		//catalog = Catalog.get(getActivity()).getRecords(); 
		
		adapter.notifyDataSetChanged();
	}
	
	
	@Override
	public void onPause()
	{
		super.onPause();
		
		
		int total = totalChecked();
		
		if(total<=0)
		{
			//if none are checked, default to the first image
			catalog.get(0).setChecked(true);
		}
		
	}
	
	
	
	private int totalChecked()
	{
		int total = 0;
		for(ImageRecord img: catalog)
		{
			if(img.getChecked()==true)
			{
				total++;
			}
		}
		return total;
	}
	
	
	
	
	private class RecordAdapter extends ArrayAdapter<ImageRecord> 
	{
		public RecordAdapter(ArrayList<ImageRecord> records) 
		{
			super(getActivity(), 0, records); 
		}
		
		
		@Override
		public View getView(int pos, View convertView, ViewGroup parent) 
		{
			if (convertView == null) 
			{
				convertView = getActivity().getLayoutInflater().inflate(R.layout.list_item,  null); 
			}
			
			final int mypos = pos;
			final ImageRecord currentImg = catalog.get(pos);
			
			//set filename		
			TextView text = (TextView) convertView.findViewById(R.id.imageName);
			text.setText(currentImg.getFilename());
			
			//set thumbnail
			ImageView thumbnail = (ImageView)convertView.findViewById(R.id.thumbnail);
			thumbnail.setImageBitmap(currentImg.getThumbnail());
			
			//set checkbox
			CheckBox checkbox = (CheckBox)convertView.findViewById(R.id.useImage);
			checkbox.setChecked(currentImg.getChecked());
			checkbox.setOnClickListener(new View.OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub					
					if(currentImg.getChecked()==true)
					{
						currentImg.setChecked(false);
					}
					else
					{
						currentImg.setChecked(true);
					}
					
					//refresh the list upon changing
					adapter.notifyDataSetChanged();
				}
			});
			
		
			return convertView; 
		}
	}
}
