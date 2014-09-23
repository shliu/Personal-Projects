package com.example.memory;

import java.io.File;
import java.util.ArrayList;
import java.util.UUID;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Environment;
import android.widget.Toast;

public class Catalog {
	private static Catalog sCat;
	private static ArrayList<ImageRecord> mRecords; 
	private static Context context;
	
	private static long lastRun;
	
	private Catalog(Context cxt) 
	{
		context = cxt;
		mRecords = new ArrayList<ImageRecord>(); 
		
		lastRun = System.currentTimeMillis();
		File file = null;
		
		// Check for SD Card
        if (!Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED)) 
        {
            Toast.makeText(context, "Error! No SDCARD Found!", Toast.LENGTH_LONG).show();
        } 
        else 
        {
        	//NOTE: THIS REQUIRES MANIFEST EDIT - ADD PERMISSION
            // Locate the image folder in your SD Card
            file = new File(
            		Environment.getExternalStorageDirectory() 
            		+ File.separator
            		+ "external_sd"
            		+ File.separator 
            		+ "DCIM" 
            		+ File.separator 
            		+ "Camera");
        }
 
        
        if (file.isDirectory()) 
        {
        	File[] listFile = file.listFiles();
 
            for (int i=0; i < listFile.length; i++) 
            {
                //Toast.makeText(context, listFile[i].getAbsolutePath()+" | "+listFile[i].getName(), Toast.LENGTH_LONG).show();
                
                ImageRecord newImg = new ImageRecord();
                
                //set filename/paths
                newImg.setFilename(listFile[i].getName());
                newImg.setFilepath(listFile[i].getAbsolutePath());
                
                //set image
                Bitmap bmp = BitmapFactory.decodeFile(listFile[i].getAbsolutePath());
                
                Bitmap thumbnail = Bitmap.createScaledBitmap(bmp, Plant.THUMBNAIL_H, Plant.THUMBNAIL_W, false);		//list thumbnail
                newImg.setThumbnail(thumbnail);
                
                Bitmap image = Bitmap.createScaledBitmap(bmp, Plant.IMAGE_H, Plant.IMAGE_H, false);		//game image
                newImg.setImage(image);
                
                mRecords.add(newImg);
            }
        }
	}
	
	
	public static Catalog get(Context cxt) 
	{
		if (sCat == null) 
		{
			sCat = new Catalog(cxt);
		}
		
		return sCat; 
	}
	
	
	public static void refresh(Context cxt)
	{
		if(sCat != null)	//only bother refreshing after first instance
		{
			File file = null;
			
			// Check for SD Card
	        if (!Environment.getExternalStorageState().equals(Environment.MEDIA_MOUNTED)) 
	        {
	            Toast.makeText(context, "Error! No SDCARD Found!", Toast.LENGTH_LONG).show();
	        } 
	        else 
	        {
	        	//NOTE: THIS REQUIRES MANIFEST EDIT - ADD PERMISSION
	            // Locate the image folder in your SD Card
	            file = new File(
	            		Environment.getExternalStorageDirectory() 
	            		+ File.separator
	            		+ "external_sd"
	            		+ File.separator 
	            		+ "DCIM" 
	            		+ File.separator 
	            		+ "Camera");
	        }
	 
	        
	        if (file.isDirectory()) 
	        {
	        	File[] listFile = file.listFiles();
	 
	            for (int i=0; i < listFile.length; i++) 
	            {
	                //Toast.makeText(context, listFile[i].getAbsolutePath()+" | "+listFile[i].getName(), Toast.LENGTH_LONG).show();
	                
	            	if(listFile[i].lastModified()>lastRun)
	            	{
	            		ImageRecord newImg = new ImageRecord();
		                
		                //set filename/paths
		                newImg.setFilename(listFile[i].getName());
		                newImg.setFilepath(listFile[i].getAbsolutePath());
		                
		                //set image
		                Bitmap bmp = BitmapFactory.decodeFile(listFile[i].getAbsolutePath());
		                
		                Bitmap thumbnail = Bitmap.createScaledBitmap(bmp, 120, 120, false);		//list thumbnail
		                newImg.setThumbnail(thumbnail);
		                
		                Bitmap image = Bitmap.createScaledBitmap(bmp, 80, 80, true);		//game image
		                newImg.setImage(image);
		                
		                mRecords.add(newImg);
	            	}
	                
	            }
	        }
		}
		
		lastRun = System.currentTimeMillis();
	}

	
	public ArrayList<ImageRecord> getRecords() 
	{
		return mRecords; 
	}
}
