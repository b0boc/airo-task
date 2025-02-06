import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { AlertsService } from '../../services/alerts.service';
import { formatDate } from '@angular/common';
import { QuoteService } from '../../services/quote.service';

@Component({
  selector: 'app-quotation',
  standalone: false,

  templateUrl: './quotation.component.html',
  styleUrl: './quotation.component.scss'
})
export class QuotationComponent {
  public quotationForm: FormGroup;

  public supportedCurrencies: Array<String> = ["EUR", "GBP", "USD"];

  public quotes: Array<any> = [];

  constructor(private formBuilder: FormBuilder, private alert: AlertsService, private quoteService: QuoteService) {
    let today = new Date();

    this.quotationForm = this.formBuilder.group({
      age: new FormControl('', [Validators.required]),
      currency_id: new FormControl('USD', [Validators.required]),
      start_date: new FormControl(formatDate(today, 'yyyy-MM-dd', 'en_us'), [Validators.required]),
      end_date: new FormControl(formatDate(today.setDate(today.getDate() + 7), 'yyyy-MM-dd', 'en_us'), [Validators.required]),
    });
  }

  getQuotation(): void {
    this.alert.clearMessage();

    if(this.quotationForm.valid) {
      this.quoteService.getQuote(this.quotationForm.value).subscribe((response: any) => {
        if(response) {
          this.quotes.push(response)
        }
      });
    }
    else {
      this.alert.showSuccess('Invalid form');
    }
  }

  
}
