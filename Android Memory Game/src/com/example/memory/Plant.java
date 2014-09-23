package com.example.memory;

import java.util.Timer;
import java.util.TimerTask;


import android.content.Context;
import android.os.Handler;
import android.os.Message;
import android.view.View;
import android.widget.Button;

public class Plant extends Button {

	private final int POT = R.drawable.pot;
	private int plantType;
	private MainActivity game;
	private boolean matched = false;
	private Plant thisPlant = this;
	
	private Timer myTimer = null;
	private CustomHandler myHandler = null;
	private final int MAX_SHOW_TIME = 3000;		//miliseconds
	
	
	public Plant(Context context) 
	{
		super(context);
		// TODO Auto-generated constructor stub
		
		game = (MainActivity)context;
		myHandler = new CustomHandler();
		
		hide();
		
		
		setOnClickListener(new View.OnClickListener()
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
			if(game.clickedPlants.size()<game.MAX_SHOW)
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
				this.setBackgroundResource(plantType);
				
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
		this.setBackgroundResource(POT);
	}
	
	
	public void reset()
	{
		hide();
		matched = false;
	}
	
	
	
	private boolean samePlantType(Plant other)
	{
		if(this!=other && this.plantType==other.plantType)
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
	
	
	
	public void setPlantType(int type)
	{
		plantType = type;
	}
	
	
	public int getPlantType()
	{
		return plantType;
	}
	
}
