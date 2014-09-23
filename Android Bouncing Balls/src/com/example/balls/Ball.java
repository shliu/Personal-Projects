package com.example.balls;

import java.util.ArrayList;
import java.util.Random;

import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.PointF;
import android.util.Log;


public class Ball {
	private PointF position;
	private PointF velocity;
	private float radius;
	private Paint color;
	
	private static final String TAG = "Ball"; 
	
	
	public Ball(float x, float y)
	{
		position = new PointF(x, y);
		velocity = new PointF(0, 0);
		
		radius = 25f;
		
		Random rand = new Random();	
		color = new Paint();	
		color.setARGB(rand.nextInt(128)+128, rand.nextInt(128)+128, rand.nextInt(128)+128, 0);
	}
	
	
	public void setVelocity(float x, float y)
	{
		velocity.x = x;
		velocity.y = y;
	}
	
	
	public boolean insideRadius(float x, float y)
	{
		if(distanceTo(x, y) <= radius)
		{
			return true;
		}
		return false;
	}
	
	
	private float distanceTo(float x, float y)
	{
		float deltaX, deltaY;
		
		deltaX = position.x - x;
		deltaY = position.y - y;
		return (float) Math.sqrt(deltaX*deltaX + deltaY*deltaY);	//simple pythagorean formula
	}
	
	
	public void update(ArrayList<Ball> balls)
	{
		//movement
		position.x += velocity.x;
		position.y += velocity.y;
		
		//drag
		velocity.x *= DrawView.getDragConstant();
		velocity.y *= DrawView.getDragConstant();
		
		//collision with other balls
		for(Ball other: balls)
		{
			if(this != other && this.collidedWith(other))
			{
				this.handleCollisionWith(other);
			}
		}
		
		//collision with canvas wall
		handleCollisionWithCanvas();
	}
	
	
	public boolean collidedWith(Ball other)
	{
		float totalRadius, distance;
		
		totalRadius = this.radius + other.radius;
		distance = distanceTo(other.position.x, other.position.y);
		
		if(distance <= totalRadius)
		{
			return true;
		}
		return false;
	}
	
	
	public void handleCollisionWith(Ball other)
	{
		//Log.i(TAG, "COLLISION: (" + this.position.x + "," + this.position.y + ")"); 
		
		//this isn't a great simulation of collision... but it kind of looks okay...
		PointF temp = this.velocity;
		this.velocity = other.velocity;		
		other.velocity = temp;
	}
	
	
	private void handleCollisionWithCanvas()
	{
		if((position.x + radius) > 400 
			|| (position.x - radius) < 0)
		{
			velocity.x = -velocity.x;
		}
		
		if((position.y + radius) > 400 
			|| (position.y - radius) < 0)
		{
			velocity.y = -velocity.y;
		}
	}
	
	
	public void draw(Canvas canvas)
	{
		canvas.drawCircle(position.x, position.y, radius, color);
	}
}
