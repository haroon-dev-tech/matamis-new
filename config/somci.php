<?php

/**
 * SOMCI line item definitions — Statement of Monthly Comprehensive Income
 */
return [
    'input_keys' => [
        'sales', 'e_commerce_online_sale', 'sales_discounts',
        'cost_of_sales',
        'salary_wages', 'admin_expenses', 'legal_professional_consultancy',
        'office_misc_expenses', 'trade_license_legal_expenses', 'office_rent_expenses',
        'utility_expenses', 'printing_stationery', 'meals_refreshments_general',
        'staff_medical_expenses', 'travel_transportation_expenses', 'employees_visa_expenses',
        'advertisement_marketing_expenses', 'repair_maintenance_expenses', 'delivery_charges_expenses',
        'directors_remuneration', 'bank_charges', 'wps_charges', 'fines_mukhalfa',
        'interest_on_loans', 'depreciation', 'other_income', 'corporate_tax',
    ],
    'rows' => [
        ['type' => 'section', 'number' => '1', 'label' => 'Revenue (Sum of all 1.1 - 1.3)'],
        ['type' => 'item', 'key' => 'sales', 'number' => '1.1', 'label' => 'Sales'],
        ['type' => 'item', 'key' => 'e_commerce_online_sale', 'number' => '1.2', 'label' => "E-Commerce's/Online Sale"],
        ['type' => 'item', 'key' => 'sales_discounts', 'number' => '1.3', 'label' => 'Sales Discounts'],

        ['type' => 'section', 'number' => '2', 'label' => 'Direct Expenses (Sum of 2.1 only)'],
        ['type' => 'item', 'key' => 'cost_of_sales', 'number' => '2.1', 'label' => 'Cost of Sales'],

        ['type' => 'calculated', 'key' => 'gross_profit_loss', 'label' => 'Gross Profit/Loss = Revenue - Direct Expenses'],

        ['type' => 'section', 'number' => '3', 'label' => 'Operating & Administrative Exp (Sum of all 3.1 - 3.15)'],
        ['type' => 'item', 'key' => 'salary_wages', 'number' => '3.1', 'label' => 'Salary & wages'],
        ['type' => 'item', 'key' => 'admin_expenses', 'number' => '3.2', 'label' => 'Admin. Expenses'],
        ['type' => 'item', 'key' => 'legal_professional_consultancy', 'number' => '3.3', 'label' => 'Legal & Professional Consultancy Service fee'],
        ['type' => 'item', 'key' => 'office_misc_expenses', 'number' => '3.4', 'label' => 'Office Misc. Expenses'],
        ['type' => 'item', 'key' => 'trade_license_legal_expenses', 'number' => '3.5', 'label' => 'Trade License & Legal Expenses'],
        ['type' => 'item', 'key' => 'office_rent_expenses', 'number' => '3.6', 'label' => 'Office Rent Expenses'],
        ['type' => 'item', 'key' => 'utility_expenses', 'number' => '3.7', 'label' => 'Utility Expenses'],
        ['type' => 'item', 'key' => 'printing_stationery', 'number' => '3.8', 'label' => 'Printing & Stationery'],
        ['type' => 'item', 'key' => 'meals_refreshments_general', 'number' => '3.9', 'label' => 'Meals & Refreshments General'],
        ['type' => 'item', 'key' => 'staff_medical_expenses', 'number' => '3.10', 'label' => 'Staff Medical Expenses'],
        ['type' => 'item', 'key' => 'travel_transportation_expenses', 'number' => '3.11', 'label' => 'Travel & Transportation Expenses'],
        ['type' => 'item', 'key' => 'employees_visa_expenses', 'number' => '3.12', 'label' => "Employee's Visa Expenses."],
        ['type' => 'item', 'key' => 'advertisement_marketing_expenses', 'number' => '3.13', 'label' => 'Advertisement & Marketing Expenses'],
        ['type' => 'item', 'key' => 'repair_maintenance_expenses', 'number' => '3.14', 'label' => 'Repair Maintenance Expenses'],
        ['type' => 'item', 'key' => 'delivery_charges_expenses', 'number' => '3.15', 'label' => 'Delivery Charges expenses'],

        ['type' => 'section', 'number' => '4', 'label' => 'Other Expenses (Sum of all 4.1-4.4)'],
        ['type' => 'item', 'key' => 'directors_remuneration', 'number' => '4.1', 'label' => "Directors' Remuneration"],
        ['type' => 'item', 'key' => 'bank_charges', 'number' => '4.2', 'label' => 'Bank Charges'],
        ['type' => 'item', 'key' => 'wps_charges', 'number' => '4.3', 'label' => 'WPS Charges'],
        ['type' => 'item', 'key' => 'fines_mukhalfa', 'number' => '4.4', 'label' => 'Fines & Mukhalfa'],

        ['type' => 'calculated', 'key' => 'indirect_expenses', 'label' => 'Indirect Expenses = Operating & Administrative Exp + Other Expenses'],

        ['type' => 'calculated', 'key' => 'profit_before_interest', 'number' => '5', 'label' => 'Profit / (Loss) before Interest, Dep. Other Income & Tax = Revenue - Direct Expense - Operating & Administrative Exp. - Other Expenses'],
        ['type' => 'item', 'key' => 'interest_on_loans', 'number' => '5.1', 'label' => 'Interest on Loans'],

        ['type' => 'calculated', 'key' => 'profit_after_interest', 'number' => '6', 'label' => 'Profit/ (Loss) After Interest but before Dep. Other Income & Tax'],
        ['type' => 'item', 'key' => 'depreciation', 'number' => '6.1', 'label' => 'Depreciation'],

        ['type' => 'calculated', 'key' => 'profit_after_dep', 'number' => '7', 'label' => 'Profit/ (Loss) After Dep. but before Other Income & Tax'],
        ['type' => 'item', 'key' => 'other_income', 'number' => '7.1', 'label' => 'Other Income'],

        ['type' => 'calculated', 'key' => 'profit_after_other_income', 'number' => '8', 'label' => 'Profit/ (Loss) After Other Income but before Tax'],
        ['type' => 'item', 'key' => 'corporate_tax', 'number' => '8.1', 'label' => 'Corporate Tax'],

        ['type' => 'calculated', 'key' => 'profit_loss', 'number' => '9', 'label' => 'Profit/Loss', 'highlight' => true],
    ],
];
