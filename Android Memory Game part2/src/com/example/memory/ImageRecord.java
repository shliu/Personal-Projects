package com.example.memory;

import java.util.Date;

import android.graphics.Bitmap;

public class ImageRecord {
	
	private boolean checked;
	private String filepath;
	private String filename;
	private Bitmap image;
	private Bitmap thumbnail;
	
	
	public ImageRecord() {
		checked = false;
	}

	//getter/setting for checked
	public boolean getChecked()
	{
		return checked;
	}
	
	public void setChecked(boolean inChecked)
	{
		checked = inChecked;
	}
	
	//getter/setting for filepath
	public String getFilepath()
	{
		return filepath;
	}

	public void setFilepath(String inFilepath)
	{
		filepath = inFilepath;
	}
	
	//getter/setting for filename
	public String getFilename()
	{
		return filename;
	}
	
	public void setFilename(String inFilename)
	{
		filename = inFilename;
	}
	
	//getter/setting for image
	public Bitmap getImage()
	{
		return image;
	}
	
	public void setImage(Bitmap inImage)
	{
		image = inImage;
	}
	
	//getter/setting for thumbnail
	public Bitmap getThumbnail()
	{
		return thumbnail;
	}
	
	public void setThumbnail(Bitmap inThumbnail)
	{
		thumbnail = inThumbnail;
	}
	
	@Override
	public String toString() {
		return filename; 
	}
}
