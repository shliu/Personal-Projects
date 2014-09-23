package com.example.memory;

import java.util.Timer;
import java.util.TimerTask;





import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Handler;
import android.os.Message;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;

public class Plant extends ImageButton {
	
	public static final int THUMBNAIL_W = 120;
	public static final int THUMBNAIL_H = 120;
	
	public static final int IMAGE_W = 80;
	public static final int IMAGE_H = 80;
	

	private Bitmap backImage;
	private ImageRecord image;
	
	private MainActivity game;
	private boolean matched = false;
	private Plant thisPlant = this;
	
	private Timer myTimer = null;
	private CustomHandler myHandler = null;
	private final int MAX_SHOW_TIME = 3000;		//milliseconds
	
	
	public Plant(Context context)
	{
		super(context);
		// TODO Auto-generated constructor stub
		
		game = (MainActivity)context;
		myHandler = new CustomHandler();
		backImage = BitmapFactory.decodeResource(getResources(), R.drawable.pot);
		backImage = Bitmap.createScaledBitmap(backImage, 80, 80, true);
		this.setPadding(0, 0, 0, 0);
		
		hide();
		
		
		this.setOnClickListener(new View.OnClickListener()
		{
			@Override
			public void onClick(View view) {
				// TODO Auto-generated method stub
				Plant plant = (Plant)view;
				plant.show();

				if(game.currentState==MainActivity.GameState.STOPPED)
				{
					game.start();
				}
				game.update();
			}
			
		});
	}
	
	
	class CustomTimerTask extends TimerTask 
	{
        @Override
        public void run() 
        {
            myHandler.sendEmptyMessage(0);
        }
    }
	

	class CustomHandler extends Handler
	{
	    @Override
	    public void handleMessage(Message msg) 
	    {
	        super.handleMessage(msg);
	       
	        myTimer.cancel();
	        myTimer= null;
	        
	        for(int i=0; i<game.clickedPlants.size(); i++)
	        {
	        	if(game.clickedPlants.get(i)==thisPlant)
	        	{
	        		game.clickedPlants.remove(i);
	        	}
	        }
	        if(!thisPlant.matched)
	        {
	        	thisPlant.hide();
	        }
	    }
	}
	
	
	
	public void show()
	{
		if(!matched)
		{
			if(game.clickedPlants.size()<game.PAIRS)
			{
				for(Plant previous: game.clickedPlants)
				{
					if(samePlantType(previous))
					{
						this.matched = true;
						previous.setMatched(true);
						break;
					}
				}
				
				game.clickedPlants.add(this);
				this.setImageBitmap(image.getImage());
				
				
				//code to make plant automatically flip over after MAX_SHOW_TIME
				if(myTimer != null)
				{
					myTimer.cancel();
					myTimer = null;
				}
				myTimer = new Timer();
				CustomTimerTask customTimerTask = new CustomTimerTask();
		        myTimer.schedule(customTimerTask, MAX_SHOW_TIME);
			}
			else
			{
				game.clickedPlants.clear();
				game.hideUnmatched();
				show();
			}
		}
	}
	

	
	
	public void hide()
	{
		this.setImageBitmap(backImage);
	}
	
	
	public void reset()
	{
		hide();
		matched = false;
	}
	
	
	
	private boolean samePlantType(Plant other)
	{
		if(this!=other && this.image==other.image)
			return true;
		
		return false;
	}
	

	private void setMatched(boolean value)
	{
		matched = value;
	}
	
	
	public boolean getMatched()
	{
		return matched;
	}
	
	
	
	public void setImage(ImageRecord inImage)
	{
		image = inImage;
	}
	
	
	public ImageRecord getImage()
	{
		return image;
	}
	
}
