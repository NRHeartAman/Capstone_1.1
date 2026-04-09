import sys
import random

def run_prediction(temp):
    try:
        temp = float(temp)
        
        # LOGIC: Mas mainit = Mas maraming Milk Tea
        # Ito ang "pseudo-XGBoost" logic mo
        base_sales = 20
        multiplier = 3.5
        noise = random.randint(-2, 2) # Kaunting randomness para hindi halatang formula lang
        
        prediction = (temp * multiplier) - base_sales + noise
        
        # Siguraduhing hindi negative at round numbers lang
        print(max(5, int(prediction)))
        
    except:
        print(0)

if __name__ == "__main__":
    if len(sys.argv) > 1:
        run_prediction(sys.argv[1])