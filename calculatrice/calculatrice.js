class Calculator {
    constructor() {
        this.previousOperandElement = document.querySelector('.previous-operand');
        this.currentOperandElement = document.querySelector('.current-operand');
        this.clear();

        // Écouter les clics sur les boutons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                if (button.hasAttribute('data-number')) {
                    this.appendNumber(button.innerText);
                } else if (button.hasAttribute('data-operation')) {
                    this.chooseOperation(button.innerText);
                } else if (button.hasAttribute('data-equals')) {
                    this.compute();
                } else if (button.hasAttribute('data-action')) {
                    switch (button.getAttribute('data-action')) {
                        case 'clear':
                            this.clear();
                            break;
                        case 'plus-minus':
                            this.toggleSign();
                            break;
                        case 'percentage':
                            this.percentage();
                            break;
                    }
                }
                this.updateDisplay();
            });
        });
    }

    clear() {
        this.currentOperand = '0';
        this.previousOperand = '';
        this.operation = undefined;
    }

    appendNumber(number) {
        if (number === '.' && this.currentOperand.includes('.')) return;
        if (this.currentOperand === '0' && number !== '.') {
            this.currentOperand = number;
        } else {
            this.currentOperand = this.currentOperand + number;
        }
    }

    chooseOperation(operation) {
        if (this.currentOperand === '') return;
        if (this.previousOperand !== '') {
            this.compute();
        }
        this.operation = operation;
        this.previousOperand = this.currentOperand + ' ' + this.operation;
        this.currentOperand = '';
    }

    compute() {
        let computation;
        const prev = parseFloat(this.previousOperand);
        const current = parseFloat(this.currentOperand);
        if (isNaN(prev) || isNaN(current)) return;

        switch (this.operation) {
            case '+':
                computation = prev + current;
                break;
            case '-':
                computation = prev - current;
                break;
            case '×':
                computation = prev * current;
                break;
            case '÷':
                computation = prev / current;
                break;
            default:
                return;
        }

        this.currentOperand = computation.toString();
        this.operation = undefined;
        this.previousOperand = '';
    }

    toggleSign() {
        this.currentOperand = (parseFloat(this.currentOperand) * -1).toString();
    }

    percentage() {
        this.currentOperand = (parseFloat(this.currentOperand) / 100).toString();
    }

    updateDisplay() {
        const formattedCurrent = this.formatDisplayNumber(this.currentOperand);
        this.currentOperandElement.innerText = formattedCurrent;
        
        // Ajuster la taille de la police si le nombre est trop long
        const currentLength = formattedCurrent.length;
        if (currentLength > 8) {
            const newSize = Math.max(20, 48 - (currentLength - 8) * 3);
            this.currentOperandElement.style.fontSize = `${newSize}px`;
        } else {
            this.currentOperandElement.style.fontSize = '48px';
        }

        if (this.operation != null) {
            this.previousOperandElement.innerText = this.formatDisplayNumber(this.previousOperand);
        } else {
            this.previousOperandElement.innerText = '';
        }
    }

    formatDisplayNumber(number) {
        const stringNumber = number.toString();
        const integerDigits = parseFloat(stringNumber.split('.')[0]);
        const decimalDigits = stringNumber.split('.')[1];
        let integerDisplay;
        
        if (isNaN(integerDigits)) {
            integerDisplay = '';
        } else {
            integerDisplay = integerDigits.toLocaleString('fr', {
                maximumFractionDigits: 0
            });
        }

        if (decimalDigits != null) {
            return `${integerDisplay}.${decimalDigits}`;
        } else {
            return integerDisplay;
        }
    }
}

// Initialiser la calculatrice
const calculator = new Calculator();
