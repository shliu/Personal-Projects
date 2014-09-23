package com.example.memory;

import java.util.ArrayList;
import java.util.Random;
import java.util.Timer;
import java.util.TimerTask;

import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.app.ActionBar.LayoutParams;
import android.app.Activity;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;

public class MainActivity extends Activity {

	public final int MAX_ROW = 4;			//# of rows
	public final int MAX_COL = 5;			//# of columns
	public final int IMG_HEIGHT = 80;		//height of image
	public final int IMG_WIDTH = 80;		//width of image
	public final int MAX_SHUFFLE = 50;		//# times to shuffle
	public final int MAX_SHOW = 2;			//# of images allowed to be shown at a time
	
	private ArrayList<Plant> plants = new ArrayList<Plant>();
	public ArrayList<Plant> clickedPlants = new ArrayList<Plant>();
	private final int plantTypes[] =
	{
		R.drawable.p01,
		R.drawable.p02,
		R.drawable.p03,
		R.drawable.p04,
		R.drawable.p05,
		R.drawable.p06,
		R.drawable.p07,
		R.drawable.p08,
		R.drawable.p09,
		R.drawable.p10
	};
	public int turns = 0;
	public boolean gameRunning = true;
	public enum GameState
	{
		RUNNING, STOPPED
	}
	public GameState currentState;
	private double startTime;
	
	private Button reset;
	private Button start;
	private TextView message;
	private RelativeLayout board;
	
	private Timer gameTimer = null;
	private CustomHandler myGameHandler = null;
		
	
	@Override
	protected void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		//find views THESE MUST COME FIRST
		reset = (Button)findViewById(R.id.reset);
		start = (Button)findViewById(R.id.start);
		message = (TextView)findViewById(R.id.message);
		board = (RelativeLayout)findViewById(R.id.board);
		
		currentState = GameState.STOPPED;
		myGameHandler = new CustomHandler();
		
		//initial game setup
		setPlants();
		shuffle();
		
		//button listeners
		reset.setOnClickListener(new View.OnClickListener() {
			@Override
            public void onClick(View v) {
				resetAll();
			}
		});
		
		
		start.setOnClickListener(new View.OnClickListener() {
			@Override
            public void onClick(View v) {
				resetAll();
				start();
			}
		});
	}
	
	
	class CustomTimerTask extends TimerTask 
	{
        @Override
        public void run() 
        {
            myGameHandler.sendEmptyMessage(0);
        }
    }
	

	class CustomHandler extends Handler
	{
	    @Override
	    public void handleMessage(Message msg) 
	    {
	        super.handleMessage(msg);

	        double diff = System.currentTimeMillis() - startTime;
	        message.setText(">> "+Math.round(diff/1000)+" <<");
	    }
	}
	
	
	public void setPlants()
	{
		//generate the plant buttons
		int curr = 0;
		for(int row=0; row<MAX_ROW; row++)
		{
			for(int col=0; col<MAX_COL; col++)
			{
				Plant plant = new Plant(this);
				plant.setPlantType(plantTypes[curr%plantTypes.length]);
				
				RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
				        LayoutParams.WRAP_CONTENT,
				        LayoutParams.WRAP_CONTENT
				);
				params.setMargins(IMG_HEIGHT*col, IMG_WIDTH*row, 0, 0);
				plant.setLayoutParams(params);
				
				plants.add(plant);
				board.addView(plant);
				
				curr++;
			}
		}
	}
	
	
	public void start()
	{
		currentState = GameState.RUNNING;
		
		startTime = System.currentTimeMillis();
		
		if(gameTimer != null)
		{
			gameTimer.cancel();
			gameTimer = null;
		}
		gameTimer = new Timer();
		CustomTimerTask customTimerTask = new CustomTimerTask();
		gameTimer.scheduleAtFixedRate(customTimerTask, 300, 100);
	}
	
	
	
	public void update()
	{
		if(gameWon())
		{
			if(gameTimer != null)
			{
				gameTimer.cancel();
				gameTimer = null;
			}
			double diff = System.currentTimeMillis() - startTime;
	        message.setText("You've won! "+Math.round(diff/1000)+"s");
			currentState = GameState.STOPPED;
		}
	}
	
	
	//check game win
	public boolean gameWon()
	{
		for(Plant plant: plants)
		{
			if(!plant.getMatched())
				return false;
		}
		
		return true;
	}
	
	
	//resets game
	public void resetAll()
	{
		if(gameTimer != null)
		{
			gameTimer.cancel();
			gameTimer = null;
		}
		shuffle();
		for(Plant plant: plants)
		{
			plant.reset();
		}
		message.setText("Good luck!");
		currentState = GameState.STOPPED;
	}
	
	
	//hides unmatched cards
	public void hideUnmatched()
	{
		for(Plant plant: plants)
		{
			if(!plant.getMatched())
			{
				plant.hide();
			}
		}
	}
	
	
	//shuffle plants
	public void shuffle()
	{
		Random gen = new Random();
		
		for(int i=0; i<MAX_SHUFFLE; i++)
		{
			int first = gen.nextInt(MAX_ROW*MAX_COL);
			int second = gen.nextInt(MAX_ROW*MAX_COL);
			swapPlants(first, second);
		}
	}
	
	
	//swap plants
	public void swapPlants(int first, int second)
	{
		int tempPlantType = plants.get(first).getPlantType();
		plants.get(first).setPlantType(plants.get(second).getPlantType());
		plants.get(second).setPlantType(tempPlantType);
	}
	
	

	@Override
	public boolean onCreateOptionsMenu(Menu menu) 
	{
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
